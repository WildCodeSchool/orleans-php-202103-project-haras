<?php

namespace App\Controller;

use App\Model\ParentManager;
use App\Model\PupilManager;

class CourseFormController extends AbstractController
{
    public function inscription(): string
    {
        $errors = [];
        $course = [];
        $thanks = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course = array_map('trim', $_POST);
            $errors = $this->validate($course);
            if (empty($errors)) {
                $parentManager = new ParentManager();
                $course['parent_id'] = $parentManager->insert($course);
                $pupilManager = new PupilManager();
                $pupilManager->insert($course);
                $course = null;
                $thanks = "Merci " . $_POST['parentfirstname'] . ", votre demande
                d'inscription pour " . $_POST['firstname'] . " à nos cours d'équitation
                a été prise en compte, nous vous recontacterons dès que possible.";
            }
        }

        return $this->twig->render('User/course_form.html.twig', [
            'course' => $course,
            'errors' => $errors,
            'thanks' => $thanks,
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
}
