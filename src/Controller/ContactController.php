<?php

namespace App\Controller;

class ContactController extends AbstractController
{
    public function contact(): string
    {
        return $this->twig->render('User/contact.html.twig');
    }
}
