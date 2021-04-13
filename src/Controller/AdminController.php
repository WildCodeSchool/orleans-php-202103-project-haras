<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function cours(): string
    {
        return $this->twig->render('Admin/cours.html.twig');
    }
}
