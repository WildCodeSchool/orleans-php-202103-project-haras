<?php

namespace App\Controller;

class AdminCourseController extends AbstractController
{
    private const INPUTS_VALIDATIONS = [
        'name' => 255,
        'day' => 8,
        'time' => '',
        'duration' => 120,
        'teacher' => '',
        'capacity' => 20,
    ];

    public function course(): string
    {
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            if (empty($errors)) {
                header('location: /adminCourse/course');
            }
        }

        return $this->twig->render('Admin/course.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
        ]);
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

        return $errors;
    }
}