<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodsMagicTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected bool $isActive = true,
        protected array $categoriesId = [],
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function update(
        string $name
    ): void {
        $this->name = $name;

        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId)
    {
        $key = array_search($categoryId, $this->categoriesId);
        if ($key !== false) {
            unset($this->categoriesId[$key]);
        }
    }

    private function validate()
    {
        DomainValidation::StrMaxLength($this->name);
        DomainValidation::StrMinLength($this->name);
    }
}
