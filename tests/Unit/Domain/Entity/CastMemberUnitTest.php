<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new DateTime(date('Y-m-d H:i:s'));
        $castMember = new EntityCastMember(
            id: new Uuid($uuid),
            name: 'New Cast Member',
            type: CastMemberType::ACTOR,
            createdAt: $createdAt,
        );

        $this->assertEquals($uuid, $castMember->id);
        $this->assertEquals('New Cast Member', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertEquals($createdAt, $castMember->createdAt);
    }

    public function testAttributesNewCastMember()
    {
        $castMember = new EntityCastMember(
            name: 'New Director Member',
            type: CastMemberType::DIRECTOR,
        );

        $this->assertNotEmpty($castMember->id);
        $this->assertEquals('New Director Member', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt);
    }

    public function testNameValidationTooShort()
    {
        $this->expectException(EntityValidationException::class);
        new EntityCastMember(
            name: 'Ne',
            type: CastMemberType::ACTOR
        );
    }

    public function testNameValidationTooLong()
    {
        $this->expectException(EntityValidationException::class);
        new EntityCastMember(
            name: str_repeat('a', 256),
            type: CastMemberType::ACTOR
        );
    }

    public function testUpdateNameValidation()
    {
        $this->expectException(EntityValidationException::class);
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new DateTime(date('Y-m-d H:i:s'));
        $castMember = new EntityCastMember(
            id: new Uuid($uuid),
            name: 'New Cast Member',
            type: CastMemberType::ACTOR,
            createdAt: $createdAt,
        );

        $castMember->update('Ne');
    }

    public function testUpdate()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new DateTime(date('Y-m-d H:i:s'));
        $castMember = new EntityCastMember(
            id: new Uuid($uuid),
            name: 'New Cast Member',
            type: CastMemberType::ACTOR,
            createdAt: $createdAt,
        );

        $this->assertEquals('New Cast Member', $castMember->name);

        $castMember->update('New Cast Member Updated');

        $this->assertEquals('New Cast Member Updated', $castMember->name);
    }
}
