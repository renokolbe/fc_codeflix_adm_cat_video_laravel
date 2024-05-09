<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\ValidatiorInterface;
use Core\Domain\Validation\VideoRakitValidator;

class VideoValidatorFactory
{
    /**
     * Efetua o Acoplamento da Validacao a ser usada (retornada)
     */
    public static function create(): ValidatiorInterface
    {
        //return new VideoLaravelValidator();
        return new VideoRakitValidator();
    }
}
