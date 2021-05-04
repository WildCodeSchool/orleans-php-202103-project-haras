<?php

namespace App\Controller;


class ContactController extends AbstractController
{
    public function contact(): string
    {
        $message = '';
        $data = [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            if (empty($data['firstname'])) {
                $errors[] = 'Le prénom est obligatoire';
            }
            if (empty($data['lastname'])) {
                $errors[] = 'Le nom est obligatoire';
            }
            if (empty($data['email'])) {
                $errors[] = 'L\'email est obligatoire';
            }
            if (empty($data['phone'])) {
                $errors[] = 'Le numero de téléphone est obligatoire';
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Mauvais format d\'email';
            }
            if (empty($data['message'])) {
                $errors[] = 'Un message est obligatoire';
            }
            if (empty($errors)) {
                $message = 'Votre message a bien été envoyé';
                $data = null;
            }
        }
        return $this->twig->render('User/contact.html.twig',[
            'success' => $message,
            'data' => $data,
            'errors' => $errors,
        ]);
    }
}
