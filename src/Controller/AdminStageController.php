<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Model\StageManager;

class AdminStageController extends AbstractController
{
    private const INPUTS_VALIDATIONS = [
        'name' => 255,
        'starting_day' => '',
        'ending_day' => '',
        'duration' => 5,
        'capacity' => 20,
        'age' => 1,
    ];

    public function stage(): string
    {
        $errors = [];
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            if (empty($errors)) {
                header('location: /adminStage/stage');
            }
        }

        return $this->twig->render('Admin/stage.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
            'button_name' => 'Enregistrer',
        ]);
    }

     public function edit(int $id): string
    {
        $errors = [];
        $stageManager = new StageManager();
        $stages = $stageManager->selectAll('starting_day');
        $formData = $stageManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);
            if (empty($errors)) {
                $formData['id'] = $id;
                $stageManager->update($formData);
                header('location: /adminStage/stage');
            }
        }

        return $this->twig->render('Admin/stage.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
            'button_name' => 'Editer',
            'stages' => $stages,
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
            $errors[] = 'Le nom du stage ne peut dépasser ' . self::INPUTS_VALIDATIONS['name'] . ' charactères.';
        }

        if (!empty($formData['starting_day']) && !$this->isDateNotPast($formData['starting_day'])) {
            $errors[] = 'La date de début de stage ne peut pas être inférieure ou égal à la date du jour';
        }

        if (!empty($formData['ending_day']) && !$this->isDateNotPast($formData['ending_day'])) {
            $errors[] = 'La date de fin de stage ne peut pas être inférieure ou égal à la date du jour';
        }

        if (!empty($formData['capacity']) && $formData['capacity'] > self::INPUTS_VALIDATIONS['capacity']) {
            $errors[] = 'Le nombre d\'élèves ne peut dépasser ' . self::INPUTS_VALIDATIONS['capacity'] . ' personnes.';
        }

        $errors = array_merge($errors, $this->minimumAge($formData), $this->minimalDurationStage($formData));

        return $errors;
    }

    /**
     * Testing if date is in past time or today
     * @param string $day
     * @return bool
     */
    public function isDateNotPast(string $day): bool
    {
        $today = new DateTime('', new DateTimeZone('Europe/Paris'));
        $day = new DateTime($day, new DateTimeZone('Europe/Paris'));

        if ($day <= $today) {
            return false;
        }

        return true;
    }

    /**
     * Testing if duration is not < self::INPUTS_VALIDATIONS['duration']
     * @param array $formData
     * @return array
     */
    public function minimalDurationStage(array $formData): array
    {
        $error = [];

        if (!empty($formData['starting_day']) && !empty($formData['ending_day'])) {
            $startingDay = (new DateTime($formData['starting_day'], new DateTimeZone('Europe/Paris')))->format('d');
            $endingDay = (new DateTime($formData['ending_day'], new DateTimeZone('Europe/Paris')))->format('d');

            if ((intval($endingDay) - intval($startingDay)) < self::INPUTS_VALIDATIONS['duration']) {
                $error[] = 'La durée ne doit pas etre inférieure à ' . self::INPUTS_VALIDATIONS['duration'] . ' jours.';
            }
        }

        return $error;
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

        if (empty($formData['starting_day'])) {
            $errors[] = 'Le jour de début doit être défini.';
        }

        if (empty($formData['ending_day'])) {
            $errors[] = 'Le jour de fin doit être défini.';
        }

        if (empty($formData['capacity'])) {
            $errors[] = 'Le nombre d\'élèves doit être défini.';
        }

        if (empty($formData['age'])) {
            $errors[] = 'L\'age doit être défini.';
        }

        return $errors;
    }
}
