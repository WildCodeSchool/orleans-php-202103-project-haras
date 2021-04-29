<?php

namespace App\Controller;

class AdminCourseInscriptionController extends AbstractController
{
    public function inscription(): string
    {
        return $this->twig->render('Admin/course_inscription.html.twig');
    }
}
