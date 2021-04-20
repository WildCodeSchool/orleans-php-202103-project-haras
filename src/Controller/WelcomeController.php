<?php

namespace App\Controller;

class WelcomeController extends AbstractController
{
    public function welcome()
    {
        return $this->twig->render('User/welcome.html.twig');
    }
}
