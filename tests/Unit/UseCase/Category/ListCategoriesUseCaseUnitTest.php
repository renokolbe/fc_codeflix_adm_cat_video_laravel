<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\DTO\Category\ListCategories\ListCategoriesOutputDTO;
use Core\UseCase\Category\ListCategoriesUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCategoriesUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function testListCategoriesEmpty()
    {
        $mockPagination = $this->mockPagination();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(ListCategoriesInputDTO::class, ['filter', 'DESC']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);

        /**
         * Spies
         */
        /*
        // Nao há necessidade do SPIES se usar no mock o parametro times() para verificar a quatidade de chamadas da funcionalidade

                $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
                $this->spy->shouldReceive('paginate')->andReturn($mockPagination);

                $useCaseSpy = new ListCategoriesUseCase($this->spy);
                $useCaseSpy->execute($this->mockInputDto);

                $this->spy->shouldHaveReceived('paginate');
        */
        $this->tearDown();
    }

    public function testListCategories()
    {

        $register = new stdClass();
        $register->id = '1';
        $register->name = 'Category 1';
        $register->description = 'Category 1 description';
        $register->is_active = true;
        $register->created_at = '2022-01-01 00:00:00';
        $register->updated_at = '2022-01-01 00:00:00';
        $register->deleted_at = '2022-01-01 00:00:00';

        $mockPagination = $this->mockPagination(
            [$register],
        );

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(ListCategoriesInputDTO::class, ['filter', 'DESC']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertInstanceOf(stdClass::class, $responseUseCase->items[0]);
        $this->assertCount(1, $responseUseCase->items);

        $this->tearDown();
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
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
