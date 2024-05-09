<?php

namespace Core\DTO\CastMember\Create;

class CastMemberCreateInputDTO
{
    public function __construct(
        public string $name,
        public int $type
    )
    {}    
}