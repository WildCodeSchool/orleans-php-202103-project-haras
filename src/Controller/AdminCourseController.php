<?php

namespace App\Controller;

use App\Model\CourseManager;
use App\Service\Sort;

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

    public function course(): string
    {
        $courseManager = new CourseManager();
        $coursesByDay = (new Sort())->sortingCoursesByDay($courseManager->selectAll('time'));
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
        $coursesByDay = (new Sort())->sortingCoursesByDay($courseManager->selectAll('time'));
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
            $errors[] = 'Le nom du cours ne peut d??passer ' . self::INPUTS_VALIDATIONS['name'] . ' charact??res.';
        }

        if (!empty($formData['day']) && strlen($formData['day']) > self::INPUTS_VALIDATIONS['day']) {
            $errors[] = 'Le jours ne peut d??passer ' . self::INPUTS_VALIDATIONS['day'] . ' charact??res.';
        }

        if (!empty($formData['duration']) && $formData['duration'] > self::INPUTS_VALIDATIONS['duration']) {
            $errors[] = 'La dur??e ne peut d??passer ' . self::INPUTS_VALIDATIONS['duration'] . ' minutes';
        }

        if (!empty($formData['capacity']) && $formData['capacity'] > self::INPUTS_VALIDATIONS['capacity']) {
            $errors[] = 'Le nombre d\'??l??ves ne peut d??passer ' . self::INPUTS_VALIDATIONS['capacity'] . ' personnes.';
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
            $errors[] = 'L\'age minimum ne peut ??tre inf??rieur ?? ' . self::INPUTS_VALIDATIONS['age'] . ' an.';
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
            $errors[] = 'Le nom du cours doit ??tre d??fini.';
        }

        if (empty($formData['day'])) {
            $errors[] = 'Le jour doit ??tre d??fini.';
        }

        if (empty($formData['time'])) {
            $errors[] = 'L\'heure doit ??tre d??finie.';
        }

        if (empty($formData['duration'])) {
            $errors[] = 'La dur??e doit ??tre d??finie.';
        }

        if (empty($formData['capacity'])) {
            $errors[] = 'Le nombre d\'??l??ves doit ??tre d??fini.';
        }

        if (empty($formData['age'])) {
            $errors[] = 'L\'age doit ??tre d??fini.';
        }

        return $errors;
    }
}
