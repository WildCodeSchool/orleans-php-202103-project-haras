<?php

namespace App\Controller;

use App\Services\THP\Formulary;

class AdminController extends AbstractController
{
    public function course(string $success = null): string
    {
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $_POST;
            $errors = (new Formulary($formData, [
                'name' => [],
                'time-start' => [],
                'duration' => [],
                'teacher' => [],
                'capacity' => [],
            ]))->validateForm();

            $formData = array_map('trim', $formData);

            if (empty($errors)) {
                header('location: /Admin/course/success');
            }
        }

        if ($success !== null) {
            $success = 'Cours enregistré avec succès!';
        }

        return $this->twig->render('Admin/course.html.twig', [
            'errors' => $errors,
            'formulaire' => $formData,
            'success' => $success,
        ]);
    }
}
