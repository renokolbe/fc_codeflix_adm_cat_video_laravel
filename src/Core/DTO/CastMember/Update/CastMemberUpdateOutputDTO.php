<?php

namespace Core\DTO\CastMember\Update;

class CastMemberUpdateOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at
    ) {
    }
}