<?php

namespace App\Services\THP;

class Formulary
{
    private array $errors = [];
    private array $form;
    private array $required;

    public function __construct(array $form, array $required)
    {
        $this->form = $form;
        $this->required = $required;
    }

    public function validateForm(): array
    {
        foreach ($this->form as $inputName => $inputValue) {
            $startMessage = 'Le champ ' . $inputName . ' ';
            $this->isInputExist($startMessage, $inputName);
            $this->isEmpty($startMessage, $inputName);
            $this->limit($startMessage, $inputName, $inputValue);
            $this->filterVar($startMessage, $inputName, $inputValue);
            $this->typeInputValue($startMessage, $inputName, $inputValue);
        }

        return $this->errors;
    }

    private function limit(string $startMessage, string $inputName, $inputValue): void
    {
        if (isset($this->required[$inputName]['limit'])) {
            $limit = $this->required[$inputName]['limit'];
            $value = $this->countStringOrInteger($inputValue);
            if (isset($limit['min'])) {
                $min = $limit['min'];
                if ($value < $min) {
                    $startMessage .= 'doit avoir plus de ' . $min;
                    $startMessage .= is_string($inputValue) ? ' caractères' : '';
                    $this->errors[] = $startMessage;
                }
            }
            if (isset($limit['max'])) {
                $max = $limit['max'];
                if ($value > $max) {
                    $startMessage .= 'doit avoir moins de ' . $max;
                    $startMessage .= is_string($inputValue) ? ' caractères' : '';
                    $this->errors[] = $startMessage;
                }
            }
        }
    }

    private function countStringOrInteger($inputValue): int
    {
        return is_string($inputValue) ? strlen($inputValue) : $inputValue;
    }

    private function isInputExist(string $startMessage, string $inputName): void
    {
        if (!array_key_exists($inputName, $this->required)) {
            $this->errors[] = $startMessage . 'n\'existe pas';
        }
    }

    private function isEmpty(string $startMessage, string $inputName): void
    {
        if (empty($this->form[$inputName])) {
            $this->errors[] = $startMessage . 'ne doit pas être vide';
        }
    }

    private function typeInputValue($startMessage, string $inputName, $inputValue): void
    {
        if (isset($this->required[$inputName]['type'])) {
            if (gettype($inputValue) !== $this->required[$inputName]['type']) {
                $this->errors[] = $startMessage . ' n\'est pas au bon format';
            }
        }
    }

    private function filterVar($startMessage, string $inputName, $inputValue): void
    {
        if (isset($this->required[$inputName]['filter_var'])) {
            if (filter_var($inputValue, $this->required[$inputName]['filter_var']) === false) {
                $this->errors[] = $startMessage . 'n\'a pas le bon format demandé.';
            }
        }
    }
}
