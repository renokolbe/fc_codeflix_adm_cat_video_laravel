<?php

namespace Tests\Feature\Core\Usecase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\DTO\Category\CreateCategory\CategoryCreateOutputDTO;
use Core\UseCase\Category\CreateCategoryUseCase;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    
    public function test_create()
    {
        $model = new ModelCategory();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new CreateCategoryUseCase($repository);

        $responseUseCase = $useCase->execute(
            new CategoryCreateInputDTO(
                name: 'test',
                description: 'test',
                isActive: true
            )
        );

        $this->assertInstanceOf(CategoryCreateOutputDTO::class, $responseUseCase);
        $this->assertNotEmpty($responseUseCase->id);
        $this->assertEquals('test', $responseUseCase->name);
        $this->assertEquals('test', $responseUseCase->description);
        $this->assertEquals(true, $responseUseCase->is_active);
        $this->assertDatabaseHas('categories', [
            'id' => $responseUseCase->id,
            'name' => $responseUseCase->name,
            'description' => $responseUseCase->description,
            'is_active' => $responseUseCase->is_active
        ]);
    }
}
