<?php

namespace App\Controller;

class CourseController extends AbstractController
{
    public function cours()
    {
        return $this->twig->render('User/course.html.twig');
    }
}
