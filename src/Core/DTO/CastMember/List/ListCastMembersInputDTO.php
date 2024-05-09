<?php

namespace Core\DTO\CastMember\List;

class ListCastMembersInputDTO
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPage = 15,
    )
    {}
}