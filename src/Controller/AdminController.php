<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function course(string $success = null): string
    {
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            $formData['duration'] = $this->intValue($formData['duration']);
            if ($formData['duration'] === 0) {
                $formData['duration'] = '';
            }
            $formData['capacity'] = $this->intValue($formData['capacity']);
            if ($formData['capacity'] === 0) {
                $formData['capacity'] = '';
            }
            if (empty($errors)) {
                header('location: /Admin/course/success');
            }
        }
        if ($success !== null) {
            $success = 'Cours enregistré avec succès!';
        }
        return $this->twig->render('Admin/course.html.twig', [
            'errors' => $errors,
            'formulary' => $formData,
            'success' => $success,
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
        define('INPUTS', [
            'name' => 255,
            'day' => 8,
            'time' => '',
            'duration' => '',
            'teacher' => '',
            'capacity' => 20,
        ]);

        $errors = [];

        foreach (array_keys($formData) as $inputName) {
            if (!array_key_exists($inputName, INPUTS)) {
                $errors[] = 'Le champs ' . $inputName . ' n\'existe pas';
            }
        }

        $testFieldsEmpty = $this->isEmpty($formData);

        if (!empty($testFieldsEmpty)) {
            return array_merge($errors, $testFieldsEmpty);
        }

        if (strlen($formData['name']) > INPUTS['name']) {
            $errors[] = 'Le nom du cours ne doit pas dépasser ' . INPUTS['name'] . ' charactères !';
        }

        if (strlen($formData['day']) > INPUTS['day']) {
            $errors[] = 'Le jours ne doit pas dépasser ' . INPUTS['day'] . ' charactères !';
        }


        if ($formData['capacity'] > INPUTS['capacity']) {
            $errors[] = 'La capacité ne doit pas dépasser ' . INPUTS['capacity'] . ' personnes !';
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
            $errors[] = 'Le nom du cours ne doit pas être vide !';
        }

        if (empty($formData['day'])) {
            $errors[] = 'Le jour doit être définie !';
        }

        if (empty($formData['time'])) {
            $errors[] = 'L\'heure doit être définie !';
        }

        if (empty($formData['duration'])) {
            $errors[] = 'La durée doit être définie !';
        }

        if (empty($formData['teacher'])) {
            $errors[] = 'Le professeur doit être définie !';
        }

        if (empty($formData['capacity'])) {
            $errors[] = 'Le nombre d\'élèves doit être définie !';
        }

        return $errors;
    }

    /**
     * change fields string into int if characters is present.
     *
     * @param string $value
     * @return integer
     */
    private function intValue(string $value): int
    {
        return intval($value, 10);
    }
}
