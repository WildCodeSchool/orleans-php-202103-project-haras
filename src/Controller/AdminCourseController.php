<?php

namespace App\Controller;

use App\Model\CourseManager;

class AdminCourseController extends AbstractController
{
    private const INPUTS_VALIDATIONS = [
        'name' => 255,
        'day' => 8,
        'time' => '',
        'duration' => 120,
        'teacher' => '',
        'capacity' => 20,
        'age' => 1,
    ];

    private const DAYS = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];

    public function course(): string
    {
        $courseManager = new CourseManager();
        $coursesByDay = $this->sortingByDay($courseManager->selectAll('time'));
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            if (empty($errors)) {
                $courseManager->insert($formData);
                header('location: /adminCourse/course');
            }
        }

        return $this->twig->render('Admin/course.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
            'courses' => $coursesByDay,
            'button_name' => 'Enregistrer',
        ]);
    }

    public function edit(int $id): string
    {
        $courseManager = new CourseManager();
        $coursesByDay = $this->sortingByDay($courseManager->selectAll('time'));
        $errors = [];
        $formData = $courseManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            if (empty($errors)) {
                $formData['id'] = $id;
                $courseManager->update($formData);
                header('location: /adminCourse/course');
            }
        }

        return $this->twig->render('Admin/course.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
            'courses' => $coursesByDay,
            'button_name' => 'Editer',
        ]);
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseManager = new CourseManager();
            $courseManager->delete($id);
            header('location: /adminCourse/course');
        }
    }

    /**
     * Test fields if exist, if not empty and test their constraints
     *
     * @param array $formData
     * @return array
     */
    private function validateFormulary(array $formData): array
    {
        $errors = [];
        $errors = array_merge($errors, $this->isEmpty($formData));

        if (!empty($formData['name']) && strlen($formData['name']) > self::INPUTS_VALIDATIONS['name']) {
            $errors[] = 'Le nom du cours ne peut dépasser ' . self::INPUTS_VALIDATIONS['name'] . ' charactères.';
        }

        if (!empty($formData['day']) && strlen($formData['day']) > self::INPUTS_VALIDATIONS['day']) {
            $errors[] = 'Le jours ne peut dépasser ' . self::INPUTS_VALIDATIONS['day'] . ' charactères.';
        }

        if (!empty($formData['duration']) && $formData['duration'] > self::INPUTS_VALIDATIONS['duration']) {
            $errors[] = 'La durée ne peut dépasser ' . self::INPUTS_VALIDATIONS['duration'] . ' minutes';
        }

        if (!empty($formData['capacity']) && $formData['capacity'] > self::INPUTS_VALIDATIONS['capacity']) {
            $errors[] = 'Le nombre d\'élèves ne peut dépasser ' . self::INPUTS_VALIDATIONS['capacity'] . ' personnes.';
        }

        $errors = array_merge($errors, $this->minimumAge($formData));

        return $errors;
    }

    /**
     * testing minimum age
     *
     * @param array $formData
     * @return array
     */
    public function minimumAge(array $formData): array
    {
        $errors = [];

        if (!empty($formData['age']) && $formData['age'] < self::INPUTS_VALIDATIONS['age']) {
            $errors[] = 'L\'age minimum ne peut être inférieur à ' . self::INPUTS_VALIDATIONS['age'] . ' an.';
        }

        return $errors;
    }

    /**
     * test fileds is empty
     *
     * @param array $formData
     * @return array
     */
    private function isEmpty(array $formData): array
    {
        $errors = [];

        if (empty($formData['name'])) {
            $errors[] = 'Le nom du cours doit être défini.';
        }

        if (empty($formData['day'])) {
            $errors[] = 'Le jour doit être défini.';
        }

        if (empty($formData['time'])) {
            $errors[] = 'L\'heure doit être définie.';
        }

        if (empty($formData['duration'])) {
            $errors[] = 'La durée doit être définie.';
        }

        if (empty($formData['capacity'])) {
            $errors[] = 'Le nombre d\'élèves doit être défini.';
        }

        if (empty($formData['age'])) {
            $errors[] = 'L\'age doit être défini.';
        }

        return $errors;
    }

    /**
     * Sorting courses by day.
     *
     * @param array $courses
     * @return array
     */
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
}
