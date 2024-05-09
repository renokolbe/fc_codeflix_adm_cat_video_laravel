<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Throwable;

class CategoryUnitTest extends TestCase
{

    public function testAttributes()
    {
        $category = new Category(
            name: 'New Category',
            description: 'New Category Description',
            isActive: true
        );

        //var_dump($category->id);
        //var_dump($category->id());
        //var_dump($category->createdAt());

        $this->assertNotEmpty($category->id());
        $this->assertNotEmpty($category->createdAt());
        $this->assertEquals('New Category', $category->name);
        $this->assertEquals('New Category Description', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function testActive(){
        $category = new Category(
            name: 'New Category',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();
        $this->assertEquals(true, $category->isActive);

    }

    public function testDisable(){
        $category = new Category(
            name: 'New Category',
            isActive: true
        );

        $this->assertTrue($category->isActive);

        $category->disable();
        $this->assertEquals(false, $category->isActive);

    }
    
    public function testUpdate(){

        $uuid = (string) Uuid::uuid4()->toString();
        $cratedAt = '2024-01-16 22:03:00';

        $category = new Category(
            id: $uuid,
            name: 'New Category',
            description: 'New Category Description',
            isActive: true,
            createdAt: $cratedAt
        );

        $category->update(
            name: 'New Category Updated',
            description: 'New Category Description Updated',
        );

        $this->assertEquals($uuid, $category->id());
        $this->assertEquals($cratedAt, $category->createdAt());
        $this->assertEquals('New Category Updated', $category->name);
        $this->assertEquals('New Category Description Updated', $category->description);

    }

    public function testExceptionName(){
        try {
            new Category(
                name: 'Ne',
                description: 'New Category Description',
            );

            $this->assertTrue(false);
            } catch (Throwable $th) {
                $this->assertInstanceOf(EntityValidationException::class, $th);
            }
    }

    public function testExceptionDescription(){
        try {
            new Category(
                name: 'New Category',
                description: random_bytes(9999),
            );

            $this->assertTrue(false);
            } catch (Throwable $th) {
                $this->assertInstanceOf(EntityValidationException::class, $th);
            }
    }
}
