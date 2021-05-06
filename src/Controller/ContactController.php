<?php

namespace App\Controller;

class ContactController extends AbstractController
{
    private const NAMELENGTH = 20;
    private const TEXTLENGTH = 1020;
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
        if (strlen($data['firstname']) > self::NAMELENGTH) {
            $errors[] = 'Le prénom doit faire moins de ' . self::NAMELENGTH . ' caractères';
        }
        $errors = [];
        if (strlen($data['lastname']) > self::NAMELENGTH) {
            $errors[] = 'Le nom doit faire moins de ' . self::NAMELENGTH . ' caractères';
        }
        $errors = [];
        if (strlen($data['message']) > self::TEXTLENGTH) {
            $errors[] = 'Le message doit faire moins de ' . self::TEXTLENGTH . ' caractères';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Mauvais format d\'email';
        }
        return $errors;
    }
}
