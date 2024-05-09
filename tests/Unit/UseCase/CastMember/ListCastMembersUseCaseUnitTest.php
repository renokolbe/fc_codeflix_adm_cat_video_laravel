<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\DTO\CastMember\List\ListCastMembersOutputDTO;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCastMembersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function testListCastMembersEmpty()
    {

        $mockPagination = $this->mockPagination();

        $mockRepo = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);

        $mockInputDto = Mockery::mock(ListCastMembersInputDTO::class, ['filter', 'DESC']);
                
        $useCase = new ListCastMembersUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);

        $this->tearDown();
    }

    public function testListCastMembers()
    {

        $register = new stdClass();
        $register->id = '1';
        $register->name = 'Actor 1';
        $register->type = 1;
        $register->created_at = '2022-01-01 00:00:00';
        $register->updated_at = '2022-01-01 00:00:00';
        $register->deleted_at = '2022-01-01 00:00:00';

        $mockPagination = $this->mockPagination(
            [$register],
        );

        $mockRepo = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);
                
        $useCase = new ListCastMembersUseCase($mockRepo);

        $mockInputDto = Mockery::mock(ListCastMembersInputDTO::class, ['filter', 'DESC', 1, 15]);

        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $responseUseCase);

        $this->assertInstanceOf(stdClass::class, $responseUseCase->items[0]);
        $this->assertCount(1, $responseUseCase->items);

        $this->tearDown();
    }
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
