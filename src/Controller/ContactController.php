<?php

namespace App\Controller;

class ContactController extends AbstractController
{
    private const LENGHT = 255;
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

            if (empty($data['message'])) {
                $errors[] = 'Un message est obligatoire';
            }
            $errors = array_merge($errors, $this->validate($data));
            if (empty($errors)) {
                $message = 'Votre message a bien été envoyé';
                $data = null;
            }
        }

        return $this->twig->render('User/contact.html.twig', [
            'success' => $message,
            'data' => $data,
            'errors' => $errors,
        ]);
    }
    private function validate(array $data): array
    {
        $errors = [];
        if (strlen($data['message']) > self::LENGHT) {
            $errors[] = 'Le message doit faire moins de 255 caractères';
        }
        if (strlen($data['phone']) > self::LENGHT) {
            $errors[] = 'Le numero de telephone doit faire moins de 12 caractères';
        }
        if (strlen($data['lastname']) > self::LENGHT) {
            $errors[] = 'Le nom doit faire moins de 255 caractères';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Mauvais format d\'email';
        }
        return $errors;
    }
}
