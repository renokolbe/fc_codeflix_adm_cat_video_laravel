<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\Delete\CastMemberDeleteOutputDTO;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    public function testeDelete()
    {
        $id = (string) RamseyUuid::uuid4()->toString();
        // arrange
        $mockRepo = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        // Expect
        $mockRepo->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $mockInputDto = Mockery::mock(CastMemberInputDTO::class, [$id]);

        $useCase = new DeleteCastMemberUseCase($mockRepo);

        // action
        $responseUseCase = $useCase->execute($mockInputDto);

        // assert
        $this->assertInstanceOf(CastMemberDeleteOutputDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

        $this->tearDown();
    }

    public function testeDeleteNotFound()
    {
        $id = (string) RamseyUuid::uuid4()->toString();

        $mockRepo = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepo->shouldReceive('delete')
            ->times(1)
            ->with($id)
            ->andReturn(false);

        $mockInputDto = Mockery::mock(CastMemberInputDTO::class, [$id]);

        $useCase = new DeleteCastMemberUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberDeleteOutputDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
