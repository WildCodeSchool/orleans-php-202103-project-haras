<?php

namespace App\Controller;

class CourseFormController extends AbstractController
{
    public function inscription()
    {
        return $this->twig->render('User/course_form.html.twig');
    }
}
