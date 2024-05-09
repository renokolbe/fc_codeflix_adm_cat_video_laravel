<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\CastMember\Create\{
    CastMemberCreateInputDTO,
    CastMemberCreateOutputDTO
};
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function testCreateNewCastMember()
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
        $mockRepo->shouldReceive('insert')->times(1)->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CastMemberCreateInputDTO::class, [$castMemberName,$castMemberType->value]);

        $useCase = new CreateCastMemberUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberCreateOutputDTO::class, $responseUseCase);
        $this->assertNotEmpty($responseUseCase->id);        
        $this->assertIsString($responseUseCase->id);        
        $this->assertEquals($castMemberName, $responseUseCase->name);
        $this->assertNotEmpty($responseUseCase->created_at);        
        $this->assertEquals($castMemberType->value, $responseUseCase->type);
    }
}
