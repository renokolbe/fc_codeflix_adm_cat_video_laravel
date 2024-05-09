<?php

namespace Core\DTO\Genre\UpdateGenre;

class GenreUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
    ) {
    }
}
