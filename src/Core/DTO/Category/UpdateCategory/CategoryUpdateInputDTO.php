<?php

namespace Core\DTO\Category\UpdateCategory;

class CategoryUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name = '',
        public ?string $description = null,
        public bool $isActive = true,
    ) {
    }
}
