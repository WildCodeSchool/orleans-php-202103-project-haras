<?php

namespace App\Controller;

use App\Model\PupilManager;
use App\Model\StageManager;
use App\Service\MinimumAge;
use App\Model\ParentManager;

class AdminStageInscriptionController extends AbstractController
{
    public function inscription(): string
    {
        $pupilManager = new PupilManager();
        $pupils = $pupilManager->selectPupilsAndParentsStage();
        return $this->twig->render('Admin/stage_inscription.html.twig', [
            'pupils' => $pupils
        ]);
    }

    public function edit(int $id): string
    {
        $stageManager = new StageManager();
        $stages = $stageManager->selectAll('starting_day');
        $pupilManager = new PupilManager();
        $stage = $pupilManager->selectPupilsStageById($id);
        $parentId = $stage['parent_id'];
        $pupilId = $stage['id'];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stage = array_map('trim', $_POST);
            $stage['parent_id'] = $parentId;
            $stage['id'] = $pupilId;
            $errors = $this->validate($stage, $stageManager);
            if ($stage['experience'] === 'false') {
                $stage['experience'] = 0;
            } elseif ($stage['experience'] === 'true') {
                $stage['experience'] = 1;
            }
            if (empty($errors)) {
                $parentManager = new ParentManager();
                $parentManager->update($stage);
                $pupilManager->update($stage);
                header('location: /adminStageInscription/Inscription');
            }
        }

        return $this->twig->render('Admin/stages_form_inscription.html.twig', [
            'stage' => $stage,
            'stages' => $stages,
            'errors' => $errors,
            'button_name' => 'Editer',
        ]);
    }

    public const MAX_FIELD_LENGTH = 255;

    private function validate(array $stage, StageManager $stageManager): array
    {
        $errors = [];
        $errors = array_merge($errors, $this->isEmpty($stage), $this->isStillEmpty($stage));
        $testAge = new MinimumAge();
        $testAge = $testAge->isSmaller($stage['birthday'], $stageManager->selectOneById($stage['stage'])['age']);
        $errors = $errors = array_merge($errors, $testAge);

        if (!empty($stage['firstname']) && strlen($stage['firstname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le pr??nom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caract??res';
        }

        if (!empty($stage['lastname']) && strlen($stage['lastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caract??res';
        }

        if (!empty($stage['parentfirstname']) && strlen($stage['parentfirstname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le pr??nom d\un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caract??res';
        }

        if (!empty($stage['parentlastname']) && strlen($stage['parentlastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom d\'un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caract??res';
        }

        return $errors;
    }

    private function isEmpty(array $stage): array
    {
        $errors = [];

        if (empty($stage['firstname'])) {
            $errors[] = 'Le pr??nom de l\'enfant est requis';
        }

        if (empty($stage['lastname'])) {
            $errors[] = 'Le nom de l\'enfant est requis';
        }

        if (empty($stage['birthday'])) {
            $errors[] = 'La date de naissance de l\'enfant est requise';
        }

        if (empty($stage['stage'])) {
            $errors[] = 'Le choix du stage est requis';
        }

        if (empty($stage['experience'])) {
            $errors[] = 'Le choix de l\'experience est requis';
        }

        return $errors;
    }

    private function isStillEmpty(array $stage): array
    {
        $errors = [];

        if (empty($stage['parentfirstname'])) {
            $errors[] = 'Le pr??nom d\'un parent est requis';
        }

        if (empty($stage['parentlastname'])) {
            $errors[] = 'Le nom d\'un parent est requis';
        }

        if (empty($stage['email'])) {
            $errors[] = 'Une adresse email est requise';
        }

        if (empty($stage['phone'])) {
            $errors[] = 'Un num??ro de t??l??phone est requis';
        }

        if (!filter_var($stage['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Votre adresse email n\'est pas valide';
        }

        return $errors;
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pupilManager = new PupilManager();
            $pupilManager->delete($id);
            header('location: /adminStageInscription/Inscription');
        }
    }
}
