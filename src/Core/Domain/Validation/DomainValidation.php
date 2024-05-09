<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptMessage ?? 'Should not be empty');
        }
    }

    public static function StrMaxLength(string $value, int $maxLength = 255, string $exceptMessage = null)
    {
        if (strlen($value) > $maxLength) {
            throw new EntityValidationException($exceptMessage ?? 'The value must be not greater than {$maxLength} characters');
        }
    }

    public static function StrMinLength(string $value, int $minLength = 3, string $exceptMessage = null)
    {
        if (strlen($value) < $minLength) {
            throw new EntityValidationException($exceptMessage ?? 'The value must be greater than {$minLength} characters');
        }
    }

    public static function StrCanBeNullAndMaxLength(string $value, int $maxLength = 255, string $exceptMessage = null)
    {
        if ((! empty($value)) && strlen($value) > $maxLength) {
            throw new EntityValidationException($exceptMessage ?? 'The value must be not greater than {$maxLength} characters');
        }
    }

}