<?php

namespace App\Controller;

class AdminStageInscriptionController extends AbstractController
{
    public function inscription(): string
    {
        return $this->twig->render('Admin/stage_inscription.html.twig');
    }
}
