<?php

namespace App\Controller;

class CourseController extends AbstractController
{
    public function Cours()
    {
        return $this->twig->render('User/course.html.twig');
    }
}
