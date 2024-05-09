<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\CategoryOutputDTO;
use Core\UseCase\Category\ListCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{

    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();
        $categoryName = 'Category 1';

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            $categoryName,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
                        ->times(1)
                        ->with($id)
                        ->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [
            $id,
        ]);
                        
        $useCase = new ListCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
        $this->assertEquals($id, $responseUseCase->id);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);
        $this->assertEquals(true, $responseUseCase->is_active);

        /**
         * Spies
         */
/*
// Nao há necessidade do SPIES se usar no mock o parametro times() para verificar a quatidade de chamadas da funcionalidade
// No caso do findById o parametro once() agrante que foi feita apenas uma chamada

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')
                        ->with($id)
                        ->andReturn($this->mockEntity);
 
        $useCaseSpy = new ListCategoryUseCase($this->spy);
        $responseUseCaseSpy = $useCaseSpy->execute($this->mockInputDto);
 
        $this->spy->shouldHaveReceived('findById');
*/
        $this->tearDown();
                               
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}