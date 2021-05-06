<?php

namespace App\Service;

use DateTime;
use DateTimeZone;

class MinimumAge
{
    public function isSmaller(string $date, int $courseAge): array
    {
        $errors = [];
        $today = new DateTime('', new DateTimeZone('Europe/Paris'));
        $birthday = new DateTime($date, new DateTimeZone('Europe/Paris'));
        if ($birthday->diff($today)->y < $courseAge || intval($birthday->format('Y')) >= intval($today->format('Y'))) {
            $errors[] = 'L\'âge de l\'enfant est inférieur à l\'âge minimum.';
        }
        return $errors;
    }
}
