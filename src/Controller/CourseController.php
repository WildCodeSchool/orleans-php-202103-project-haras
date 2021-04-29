<?php

namespace App\Controller;

use App\Model\CourseManager;

class CourseController extends AbstractController
{
    public function course()
    {
        $imagesCarousel = ['faceharas.jpg', 'poneycarousel.jpg', 'prairieharas.jpg'];
        $courses = (new CourseManager())->selectDistinctName();
        return $this->twig->render('User/course.html.twig', [
            'courses' => $courses,
            'images' => $imagesCarousel,
        ]);
    }
}
