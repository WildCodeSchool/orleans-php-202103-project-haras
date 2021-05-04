<?php

namespace App\Controller;

use App\Model\StageManager;
use App\Model\PupilManager;
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
}
