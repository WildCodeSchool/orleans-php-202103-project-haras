<?php

namespace App\Controller;

class StageController extends AbstractController
{
    public function stage()
    {
        return $this->twig->render('User/stage.html.twig');
    }
}
