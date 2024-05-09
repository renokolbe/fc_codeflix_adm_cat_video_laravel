<?php

namespace Core\DTO\CastMember;

class CastMemberOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at
    ) {
    }
}