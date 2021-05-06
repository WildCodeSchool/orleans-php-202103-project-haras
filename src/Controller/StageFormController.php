<?php

namespace App\Controller;

use App\Model\StageManager;
use App\Model\ParentManager;
use App\Model\PupilManager;
use App\Model\StagingManager;
use App\Service\MinimumAge;

class StageFormController extends AbstractController
{
    public function inscription(): string
    {
        $stageManager = new StageManager();
        $stages = $stageManager->selectAll('starting_day');
        $errors = [];
        $stage = [];
        $thanks = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stage = array_map('trim', $_POST);
            $errors = $this->validate($stage, $stageManager);
            if ($stage['experience'] === 'false') {
                $stage['experience'] = 0;
            } elseif ($stage['experience'] === 'true') {
                $stage['experience'] = 1;
            }
            if (empty($errors)) {
                $parentManager = new ParentManager();
                $stage['parent_id'] = $parentManager->insert($stage);
                $pupilManager = new PupilManager();
                $stage['pupil_id'] = $pupilManager->insert($stage);
                $stagingManager = new StagingManager();
                $stagingManager->insert($stage);
                $thanks = "Merci " . $stage['parentfirstname'] . ", votre demande
                d'inscription pour " . $stage['firstname'] . " à nos stages d'équitation
                a été prise en compte, nous vous recontacterons dès que possible.";
                $stage = null;
            }
        }

        return $this->twig->render('User/stages_form_inscription.html.twig', [
            'stage' => $stage,
            'errors' => $errors,
            'thanks' => $thanks,
            'stages' => $stages,
            'button_name' => 'S\'inscrire',
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
            $errors[] = 'Le prénom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($stage['lastname']) && strlen($stage['lastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom de l\'enfant doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($stage['parentfirstname']) && strlen($stage['parentfirstname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le prénom d\un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        if (!empty($stage['parentlastname']) && strlen($stage['parentlastname']) > self::MAX_FIELD_LENGTH) {
            $errors[] = 'Le nom d\'un parent doit faire moins de ' . self::MAX_FIELD_LENGTH . ' caractères';
        }

        return $errors;
    }

    private function isEmpty(array $stage): array
    {
        $errors = [];

        if (empty($stage['firstname'])) {
            $errors[] = 'Le prénom de l\'enfant est requis';
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
            $errors[] = 'Le prénom d\'un parent est requis';
        }

        if (empty($stage['parentlastname'])) {
            $errors[] = 'Le nom d\'un parent est requis';
        }

        if (empty($stage['email'])) {
            $errors[] = 'Une adresse email est requise';
        }

        if (empty($stage['phone'])) {
            $errors[] = 'Un numéro de téléphone est requis';
        }

        if (!filter_var($stage['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Votre adresse email n\'est pas valide';
        }

        return $errors;
    }
}
