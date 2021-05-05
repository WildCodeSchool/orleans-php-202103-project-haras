<?php

namespace App\Controller;

use App\Model\StageManager;

class StageController extends AbstractController
{
    public function stage(): string
    {
        $stageManager = new StageManager();
        $stages = $stageManager->selectAll('starting_day');
        $imagesCarousel = ['stage1.jpg', 'stage2.jpg', 'stage3.jpg'];
        return $this->twig->render('User/stage.html.twig', [
            'images' => $imagesCarousel,
            'stages' => $stages,
        ]);
    }
}
