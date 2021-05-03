<?php

namespace App\Controller;

class StageController extends AbstractController
{
    public function stage(): string
    {
        $imagesCarousel = ['stage1.jpg', 'stage2.jpg', 'stage3.jpg'];
        return $this->twig->render('User/stage.html.twig', [
            'images' => $imagesCarousel,
        ]);
    }
}
