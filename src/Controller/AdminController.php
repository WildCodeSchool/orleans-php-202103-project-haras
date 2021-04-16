<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function course(): string
    {
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            $formData['duration'] = intval($formData['duration'], 10);
            if ($formData['duration'] === 0) {
                $formData['duration'] = '';
            }
            $formData['capacity'] = intval($formData['capacity'], 10);
            if ($formData['capacity'] === 0) {
                $formData['capacity'] = '';
            }
            if (empty($errors)) {
                return $this->twig->render('Admin/course.html.twig', [
                    'formulary' => $formData,
                    'success' => 'Cours enregistré avec succès!',
                ]);
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
        define('INPUTS_VALIDATIONS', [
            'name' => 255,
            'day' => 8,
            'time' => '',
            'duration' => 120,
            'teacher' => '',
            'capacity' => 20,
        ]);

        $errors = [];

        foreach (array_keys($formData) as $inputName) {
            if (!array_key_exists($inputName, INPUTS_VALIDATIONS)) {
                $errors[] = 'Le champs ' . $inputName . ' n\'existe pas.';
            }
        }

        $testFieldsEmpty = $this->isEmpty($formData);

        if (!empty($testFieldsEmpty)) {
            return array_merge($errors, $testFieldsEmpty);
        }

        if (strlen($formData['name']) > INPUTS_VALIDATIONS['name']) {
            $errors[] = 'Le nom du cours ne doit pas dépasser ' . INPUTS_VALIDATIONS['name'] . ' charactères.';
        }

        if (strlen($formData['day']) > INPUTS_VALIDATIONS['day']) {
            $errors[] = 'Le jours ne doit pas dépasser ' . INPUTS_VALIDATIONS['day'] . ' charactères.';
        }

        if ($formData['duration'] > INPUTS_VALIDATIONS['duration']) {
            $errors[] = 'La durée ne doit pas dépasser ' . INPUTS_VALIDATIONS['duration'] . ' minutes';
        }


        if ($formData['capacity'] > INPUTS_VALIDATIONS['capacity']) {
            $errors[] = 'La capacité ne doit pas dépasser ' . INPUTS_VALIDATIONS['capacity'] . ' personnes.';
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
            $errors[] = 'Le nom du cours ne doit pas être vide.';
        }

        if (empty($formData['day'])) {
            $errors[] = 'Le jour doit être défini.';
        }

        if (empty($formData['time'])) {
            $errors[] = 'L\'heure doit être défini.';
        }

        if (empty($formData['duration'])) {
            $errors[] = 'La durée doit être définie.';
        }

        if (empty($formData['teacher'])) {
            $errors[] = 'Le professeur doit être défini.';
        }

        if (empty($formData['capacity'])) {
            $errors[] = 'Le nombre d\'élèves doit être défini.';
        }

        return $errors;
    }
}
