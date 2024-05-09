<?php

namespace Core\DTO\CastMember\Update;

class CastMemberUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name
    ) {
    }
}
