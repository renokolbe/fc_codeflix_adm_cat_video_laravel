<?php

namespace Core\UseCase\Video\ChangeEncoded\DTO;

class ChangeEncodedVideoInputDTO
{
    public function __construct(
        public string $id,
        public string $encodedPath,
    ) {
    }
}
