<?php

namespace App\Controller;

class StagesInscriptionsController extends AbstractController
{
    public function stages(): string
    {
        return $this->twig->render('User/stages_form_inscription.html.twig');
    }
}
