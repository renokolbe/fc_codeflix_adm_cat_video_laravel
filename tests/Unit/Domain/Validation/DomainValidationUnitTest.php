<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {

        try {
            $value = '';
            DomainValidation::notNull($value);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullExceptMessage()
    {

        try {
            $value = '';
            DomainValidation::notNull($value, 'custom message');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message');
        }
    }

    public function testStrMaxLength()
    {

        try {
            $value = 'palavra';
            DomainValidation::StrMaxLength($value, 5, 'new custom message');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message');
        }

    }

    public function testStrMinLength()
    {

        try {
            $value = 'palavra';
            DomainValidation::StrMinLength($value, 10, 'new custom message');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message');
        }

    }

    public function testStrCanBeNullAndMaxLength()
    {

        try {
            $value = 'palavra';
            DomainValidation::StrCanBeNullAndMaxLength($value, 5, 'new custom message');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message');
        }

    }
}
