<?php

namespace App\Controller;

use App\Model\CourseManager;
use DateTime;

class CourseController extends AbstractController
{
    public function course()
    {
        $courses = (new CourseManager())->selectDistinctName();
        return $this->twig->render('User/course.html.twig', [
            'courses' => $courses,
        ]);
    }
}
