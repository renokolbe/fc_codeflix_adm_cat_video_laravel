<?php

namespace Core\DTO\Genre\List;

class ListGenresInputDTO
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPage = 15,
    ) {
    }
}
