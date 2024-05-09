<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\UseCase\Category\CreateCategoryUseCase;
use \Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\DTO\Category\CreateCategory\CategoryCreateOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Category 1';

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('insert')->times(1)->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryCreateInputDTO::class, [
            $categoryName,
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryCreateOutputDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);
        $this->assertEquals(true, $responseUseCase->is_active);

        /**
         * Spies
         */
/*
// Nao há necessidade do SPIES se usar no mock o parametro times() para verificar a quatidade de chamadas da funcionalidade
         $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
         $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);
 
         $useCaseSpy = new CreateCategoryUseCase($this->spy);
         $responseUseCaseSpy = $useCaseSpy->execute($this->mockInputDto);
 
         $this->spy->shouldHaveReceived('insert');
*/
        Mockery::close();
    }
}