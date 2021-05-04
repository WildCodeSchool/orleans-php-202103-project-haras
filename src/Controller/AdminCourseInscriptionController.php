<?php

namespace App\Controller;

use App\Model\CourseManager;
use App\Model\PupilManager;
use App\Model\ParentManager;

class AdminCourseInscriptionController extends AbstractController
{
    private const DAYS = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];

    public function inscription(): string
    {
        $pupilManager = new PupilManager();
        $pupils = $pupilManager->selectPupilsAndParents();
        return $this->twig->render('Admin/course_inscription.html.twig', [
            'pupils' => $pupils
        ]);
    }

    public function edit(int $id): string
    {
        $courseManager = new CourseManager();
        $pupilManager = new PupilManager();
        $course = $pupilManager->selectPupilsAndParentsById($id);
        $parentId = $course['parent_id'];
        $pupilId = $course['id'];
        $listingCourses = $this->sortingByDay($courseManager->selectAll());
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course = array_map('trim', $_POST);
            $course['parent_id'] = $parentId;
            $course['id'] = $pupilId;
            $errors = $this->validate($course);
            if (empty($errors)) {
                $parentManager = new ParentManager();
                $parentManager->update($course);
                $pupilManager->update($course);
                header('location: /adminCourseInscription/Inscription');
            }
        }

        return $this->twig->render('User/course_form.html.twig', [
            'course' => $course,
            'errors' => $errors,
            'courses_select' => $listingCourses,
            'button_name' => 'Editer',
        ]);
    }

    public const MAX_FIELD_LENGTH = 255;

    private function validate(array $course): array
    {
        $errors = [];
        $errors = array_merge($errors, $this->isEmpty($course), $this->isStillEmpty($course));

        if (!empty($course['firstname']) && strlen($course['firstname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le prénom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($course['lastname']) && strlen($course['lastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($course['parentfirstname']) && strlen($course['parentfirstname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le prénom d\un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($course['parentlastname']) && strlen($course['parentlastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom d\'un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        return $errors;
    }

    private function isEmpty(array $course): array
    {
        $errors = [];

        if (empty($course['firstname'])) {
            $errors[] = 'Le prénom de l\'enfant est requis';
        }

        if (empty($course['lastname'])) {
            $errors[] = 'Le nom de l\'enfant est requis';
        }

        if (empty($course['birthday'])) {
            $errors[] = 'La date de naissance de l\'enfant est requise';
        }

        if (empty($course['course'])) {
            $errors[] = 'Le choix du cours est requis';
        }

        if (empty($course['experience'])) {
            $errors[] = 'Le choix de l\'experience est requis';
        }

        return $errors;
    }

    private function isStillEmpty(array $course): array
    {
        $errors = [];

        if (empty($course['parentfirstname'])) {
            $errors[] = 'Le prénom d\'un parent est requis';
        }

        if (empty($course['parentlastname'])) {
            $errors[] = 'Le nom d\'un parent est requis';
        }

        if (empty($course['email'])) {
            $errors[] = 'Une adresse email est requise';
        }

        if (empty($course['phone'])) {
            $errors[] = 'Un numéro de téléphone est requis';
        }

        if (!filter_var($course['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Votre adresse email n\'est pas valide';
        }

        return $errors;
    }

    private function sortingByDay(array $courses): array
    {
        $coursesByDay = [];
        foreach ($courses as $course) {
            $course['dayString'] = self::DAYS[$course['day']];
            $coursesByDay[$course['day']][] = $course;
        }
        ksort($coursesByDay);

        return $coursesByDay;
    }
    
    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pupilManager = new PupilManager();
            $pupilManager->delete($id);
            header('location: /adminCourseInscription/Inscription');
        }
    }
}
