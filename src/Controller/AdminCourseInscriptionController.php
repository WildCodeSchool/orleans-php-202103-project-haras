<?php

namespace App\Controller;

use App\Model\PupilManager;

class AdminCourseInscriptionController extends AbstractController
{
    public function inscription(): string
    {
        $pupilManager = new PupilManager();
        $pupils = $pupilManager->selectPupilsAndParents();
        return $this->twig->render('Admin/course_inscription.html.twig', [
            'pupils' => $pupils
        ]);
    }
}
