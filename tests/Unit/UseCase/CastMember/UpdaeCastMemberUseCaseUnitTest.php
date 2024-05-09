<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\CastMember\Update\CastMemberUpdateInputDTO;
use Core\DTO\CastMember\Update\CastMemberUpdateOutputDTO;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdaeCastMemberUseCaseUnitTest extends TestCase
{   
    public function testUpdate()
    {
        $uuid = (string) RamseyUuid::uuid4()->toString();
        $castMemberName = 'Actor 1';
        $castMemberType = CastMemberType::ACTOR;

        $mockEntity =  Mockery::mock(CastMember::class, [
            $castMemberName,
            $castMemberType,
            new Uuid($uuid),
        ]);
        
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update')->times(1);

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);        
        $mockRepository->shouldReceive('findById')->once()->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')->times(1)->andReturn($mockEntity);

        $useCase = new UpdateCastMemberUseCase($mockRepository);

        $castMemberNewName = 'Actor 1 Renamed';

        $mockInputDto = Mockery::mock(CastMemberUpdateInputDTO::class, [
            $uuid, $castMemberNewName,
        ]);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberUpdateOutputDTO::class, $response);

        $this->assertIsString($response->id);
        $this->assertEquals($castMemberNewName, $response->name);
        $this->assertEquals($castMemberType->value, $response->type);
        $this->assertNotEmpty($response->created_at);

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
