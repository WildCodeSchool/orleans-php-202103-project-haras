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
        $stageManager = new StageManager();
        $stages = $stageManager->selectAll('starting_day');
        $errors = [];
        $formData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_map('trim', $_POST);
            $errors = $this->validateFormulary($formData);

            if (empty($errors)) {
                $stageManager->insert($formData);
                header('location: /adminStage/stage');
            }
        }

        return $this->twig->render('Admin/stage.html.twig', [
            'formulary' => $formData,
            'errors' => $errors,
            'button_name' => 'Enregistrer',
            'stages' => $stages,
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

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stageManager = new StageManager();
            $stageManager->delete($id);
            header('location: /adminStage/stage');
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
            $errors[] = 'Le nom du stage ne peut d??passer ' . self::INPUTS_VALIDATIONS['name'] . ' charact??res.';
        }

        if (!empty($formData['starting_day']) && !$this->isDateNotPast($formData['starting_day'])) {
            $errors[] = 'La date de d??but de stage ne peut pas ??tre inf??rieure ou ??gal ?? la date du jour';
        }

        if (!empty($formData['ending_day']) && !$this->isDateNotPast($formData['ending_day'])) {
            $errors[] = 'La date de fin de stage ne peut pas ??tre inf??rieure ou ??gal ?? la date du jour';
        }

        if (!empty($formData['capacity']) && $formData['capacity'] > self::INPUTS_VALIDATIONS['capacity']) {
            $errors[] = 'Le nombre d\'??l??ves ne peut d??passer ' . self::INPUTS_VALIDATIONS['capacity'] . ' personnes.';
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
                $error[] = 'La dur??e ne doit pas etre inf??rieure ?? ' . self::INPUTS_VALIDATIONS['duration'] . ' jours.';
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

        if (empty($formData['starting_day'])) {
            $errors[] = 'Le jour de d??but doit ??tre d??fini.';
        }

        if (empty($formData['ending_day'])) {
            $errors[] = 'Le jour de fin doit ??tre d??fini.';
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
