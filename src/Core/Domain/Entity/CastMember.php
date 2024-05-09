<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicTrait;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMember
{
    use MethodsMagicTrait;

    public function __construct(
        protected string $name,
        protected CastMemberType $type,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
        $this->validate();
    }

    public function update(
        string $name
    ): void {
        $this->name = $name;

        $this->validate();
    }

    private function validate()
    {
        DomainValidation::StrMaxLength($this->name);
        DomainValidation::StrMinLength($this->name);
    }
}
