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

    /*public function edit(int $id): string
    {
        $courseManager = new CourseManager();
        $pupilManager = new PupilManager();
        $listingCourses = $this->sortingByDay($courseManager->selectAll());
        $errors = [];
        $course = $pupilManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course = array_map('trim', $_POST);
            $errors = $this->validate($course);
            if (empty($errors)) {
                $parentManager = new ParentManager();
                $course['parent_id'] = $parentManager->update($course);
                $course['id'] = $id;
                $pupilManager->update($course);
                header('location: User/CourseForm/Inscription');
            }
        }

        return $this->twig->render('User/course_form.html.twig', [
            'course' => $course,
            'errors' => $errors,
            'courses_select' => $listingCourses,
            'button_name' => 'Editer',
        ]);
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
    }*/
}
