<?php

namespace Core\DTO\CastMember\Delete;

class CastMemberDeleteOutputDTO
{
    public function __construct(
        public bool $success
    ) {
    }
}