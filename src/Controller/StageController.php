<?php

namespace App\Controller;

class StageController extends AbstractController
{
    public function stage(): string
    {
        return $this->twig->render('User/stage.html.twig');
    }
}
