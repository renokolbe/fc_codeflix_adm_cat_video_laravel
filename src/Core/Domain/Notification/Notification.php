<?php

namespace Core\Domain\Notification;

class Notification{

    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }
    /**
     * @param string $error array [context, message]
     */

    public function addError(array $error): void
    {
        array_push($this->errors, $error);
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function messages(string $context = ''): string
    {
        $messages = '';
        foreach ($this->errors as $error) {
            if ($context === '' || $error['context'] === $context){
                $messages .= "{$error['context']}: {$error['message']},";
            }
        }
        
        return $messages;
    }
}