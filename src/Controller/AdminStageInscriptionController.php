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

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pupilManager = new PupilManager();
            $pupilManager->delete($id);
            header('location: /adminStageInscription/Inscription');
        }
    }
}
