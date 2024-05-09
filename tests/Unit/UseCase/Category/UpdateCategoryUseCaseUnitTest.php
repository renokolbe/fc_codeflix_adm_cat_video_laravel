<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function testRenameCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Category Name 1';
        $categoryDesc = 'Category Description 1';

        $this->mockEntity = Mockery::mock(EntityCategory::class, [
            $uuid,
            $categoryName,
            $categoryDesc,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockEntity->shouldReceive('update');

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')->once()->with($uuid)->andReturn($this->mockEntity);
        $this->mockRepo->shouldReceive('update')->times(1)->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDTO::class, [
            $uuid,
            'New Category Name 1',
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryUpdateOutputDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        //$this->assertEquals('New Category Name 1', $responseUseCase->name);
        //$this->assertEquals('', $responseUseCase->description);
        //$this->assertEquals(true, $responseUseCase->isActive);

        /**
         * Spies
         */
        /*
        // Nao há necessidade do SPIES se usar no mock o parametro times() para verificar a quatidade de chamadas da funcionalidade
        // No caso do findById o parametro once() agrante que foi feita apenas uma chamada

                 $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
                 $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
                 $this->spy->shouldReceive('update')->andReturn($this->mockEntity);

                 $useCaseSpy = new UpdateCategoryUseCase($this->spy);
                 $responseUseCaseSpy = $useCaseSpy->execute($this->mockInputDto);

                 $this->spy->shouldHaveReceived('findById');
                 $this->spy->shouldHaveReceived('update');
        */
        Mockery::close();
    }
}
