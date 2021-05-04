<?php

namespace App\Service;

class Sort
{
    private const DAYS = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];

    public function sortingCoursesByDay(array $courses): array
    {
        $coursesByDay = [];
        foreach ($courses as $course) {
            $course['dayString'] = self::DAYS[$course['day']];
            $coursesByDay[$course['day']][] = $course;
        }
        ksort($coursesByDay);

        return $coursesByDay;
    }
}
