<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;

interface ValidatiorInterface
{
    public function validate(Entity $entity): void;
}