<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

//use Core\Domain\Exception\EntityValidationException;

class Category extends Entity
{
    //use MethodsMagicTrait; // Nao eh mais necessario pois esta classe agora extende a Entity que jah contem os metodos

    public function __construct(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = ''
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(
        string $name,
        string $description = ''
    ): void {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    private function validate()
    {
        /*

        // Subtituido pelo Teste de Dominio Generico

                if(empty($this->name)){
                    throw new EntityValidationException('Name is required');
                }

                if(strlen($this->name) < 3 || strlen($this->name) > 255) {
                    throw new EntityValidationException('Name must be between 3 and 255 characters');
                }

                if ($this->description != '' && (strlen($this->description) < 3 || strlen($this->description) > 255)) {
                    throw new EntityValidationException('Description must be between 3 and 255 characters');
                }
        */

        DomainValidation::StrMaxLength($this->name);
        DomainValidation::StrMinLength($this->name);
        DomainValidation::StrCanBeNullAndMaxLength($this->description);
    }
}
