<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function cours()
    {
        return $this->twig->render('Admin/cours.html.twig');
    }
}
