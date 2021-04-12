<?php

namespace App\Controller;

use App\Service\library\THP\Formulary;

class AdminController extends AbstractController
{
    public function cours()
    {
        return $this->twig->render('Admin/cours.html.twig');
    }
}
