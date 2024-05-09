<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\CastMemberOutputDTO;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class ListCastMemberUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $uuid = (string) RamseyUuid::uuid4()->toString();
        $castMemberName = 'Actor 1';
        $castMemberType = CastMemberType::ACTOR;

        $mockEntity = Mockery::mock(CastMember::class, [
            $castMemberName,
            $castMemberType,
            new Uuid($uuid),
        ]);

        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepo = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepo->shouldReceive('findById')
            ->with($uuid)
            ->times(1)
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CastMemberInputDTO::class, [
            $uuid,
        ]);

        $useCase = new ListCastMemberUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberOutputDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals($castMemberName, $responseUseCase->name);

        $this->tearDown();

    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
