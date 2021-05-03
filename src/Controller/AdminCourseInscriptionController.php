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

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pupilManager = new PupilManager();
            $pupilManager->delete($id);
            header('location: /adminCourseInscription/Inscription');
        }
    }
}
