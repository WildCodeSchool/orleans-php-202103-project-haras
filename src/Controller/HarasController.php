<?php

namespace App\Controller;

class HarasController extends AbstractController
{
    public function haras()
    {
        return $this->twig->render('User/haras.html.twig');
    }
}