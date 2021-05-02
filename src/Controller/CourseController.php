<?php

namespace App\Controller;

use App\Model\CourseManager;
use App\Service\Sort;

class CourseController extends AbstractController
{
    public function course()
    {
        $courseManager = new CourseManager();
        $imagesCarousel = ['faceharas.jpg', 'poneycarousel.jpg', 'prairieharas.jpg'];
        $courses = $courseManager->selectDistinctName();
        $coursesByDay = (new Sort())->sortingCoursesByDay($courseManager->selectAll('time'));
        return $this->twig->render('User/course.html.twig', [
            'courses' => $courses,
            'images' => $imagesCarousel,
            'coursesByDay' => $coursesByDay,
        ]);
    }
}
