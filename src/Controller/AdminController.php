<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function course(): string
    {
        return $this->twig->render('Admin/course.html.twig');
    }
}
