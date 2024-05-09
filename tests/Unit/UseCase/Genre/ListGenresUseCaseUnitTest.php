<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\DTO\Genre\List\ListGenresInputDTO;
use Core\DTO\Genre\List\ListGenresOutputDTO;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListGenresUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_usecase()
    {
        $mockPagination = $this->mockPagination();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->times(1)->andReturn($mockPagination);

        $mockDTOInput = Mockery::mock(ListGenresInputDTO::class, [
            'test', 'ASC', 1, 15,
        ]);

        $useCase = new ListGenresUseCase($mockRepository);
        $response = $useCase->execute($mockDTOInput);

        $this->assertInstanceOf(ListGenresOutputDTO::class, $response);

        Mockery::close();

        /**
         * Spies
         */

        // arrange
        $mockSpy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $mockSpy->shouldReceive('paginate')->times(1)->andReturn($mockPagination);
        $useCaseSpy = new ListGenresUseCase($mockSpy);

        // action

        $useCaseSpy->execute($mockDTOInput);

        // assert
        $mockSpy->shouldHaveReceived()->paginate(
            'test', 'ASC', 1, 15
        );
    }
    /*
        protected function mockPagination(array $items = [], )
        {
            $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
            $this->mockPagination->shouldReceive('items')->andReturn($items);
            $this->mockPagination->shouldReceive('total')->andReturn(0);
            $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
            $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
            $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
            $this->mockPagination->shouldReceive('perPage')->andReturn(0);
            $this->mockPagination->shouldReceive('to')->andReturn(0);
            $this->mockPagination->shouldReceive('from')->andReturn(0);

            return $this->mockPagination;

        }
    */
}
