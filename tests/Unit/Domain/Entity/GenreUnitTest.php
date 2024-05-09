<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new DateTime(date('Y-m-d H:i:s'));
        $genre = new EntityGenre(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: false,
            createdAt: $createdAt,
        );

        $this->assertEquals($uuid, $genre->id);
        $this->assertEquals('New Genre', $genre->name);
        $this->assertFalse($genre->isActive);
        $this->assertEquals($createdAt, $genre->createdAt);
    }

    public function testCreateGenreWithDefaultValues()
    {
        $genre = new EntityGenre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id);
        $this->assertEquals('New Genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertNotEmpty($genre->createdAt);

    }

    public function testActive()
    {
        $genre = new EntityGenre(
            name: 'New Genre',
            isActive: false
        );

        $this->assertFalse($genre->isActive);

        $genre->activate();
        $this->assertEquals(true, $genre->isActive);

    }

    public function testDeactivate()
    {
        $genre = new EntityGenre(
            name: 'New Genre'
        );

        $this->assertTrue($genre->isActive);

        $genre->deactivate();
        $this->assertEquals(false, $genre->isActive);

    }

    public function testUpdate()
    {

        $genre = new EntityGenre(
            name: 'New Genre',
        );

        $this->assertEquals('New Genre', $genre->name);

        $genre->update(
            name: 'New Genre Updated',
        );

        $this->assertEquals('New Genre Updated', $genre->name);

    }

    public function testCreateException()
    {
        $this->expectException(EntityValidationException::class);
        new EntityGenre(
            name: 'Ne',
        );
    }

    public function testUpdateException()
    {
        $this->expectException(EntityValidationException::class);
        $genre = new EntityGenre(
            name: 'New Genre',
        );

        $genre->update(
            name: 'Ne',
        );
    }

    public function testAddCategoryToGenre()
    {
        $genre = new EntityGenre(
            name: 'New Genre',
        );

        $this->assertIsArray($genre->categoriesId);
        $this->assertCount(0, $genre->categoriesId);

        $uuid1 = (string) RamseyUuid::uuid4();

        $genre->addCategory(
            categoryId: new Uuid($uuid1)
        );

        $uuid2 = (string) RamseyUuid::uuid4();

        $genre->addCategory(
            categoryId: new Uuid($uuid2)
        );

        $this->assertCount(2, $genre->categoriesId);

    }

    public function testRemoveCategoryFromGenre()
    {

        $uuid1 = (string) RamseyUuid::uuid4();
        $uuid2 = (string) RamseyUuid::uuid4();

        $genre = new EntityGenre(
            name: 'New Genre',
            categoriesId: [$uuid1, $uuid2]
        );

        $this->assertCount(2, $genre->categoriesId);

        $genre->removeCategory(
            categoryId: $uuid1
        );

        //dump($genre->categoriesId);

        $this->assertCount(1, $genre->categoriesId);
        //$this->assertArrayHasKey($uuid2, $genre->categoriesId);
        $this->assertEquals($uuid2, $genre->categoriesId[1]);

    }
}
