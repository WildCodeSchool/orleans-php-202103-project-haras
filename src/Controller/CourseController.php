<?php

namespace App\Controller;

class CourseController extends AbstractController
{
    public function course()
    {
        return $this->twig->render('User/course.html.twig');
    }
}
