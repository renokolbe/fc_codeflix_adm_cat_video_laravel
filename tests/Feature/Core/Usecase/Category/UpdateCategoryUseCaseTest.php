<?php

namespace Tests\Feature\Core\Usecase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function test_update()
    {
        $categoryDB = ModelCategory::factory()->create();

        $model = new ModelCategory();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new UpdateCategoryUseCase($repository);

        $resposeUseCase = $useCase->execute(
            new CategoryUpdateInputDTO(
                id: $categoryDB->id,
                name: 'New Category Name',
            )
        );

        $this->assertInstanceOf(CategoryUpdateOutputDTO::class, $resposeUseCase);
        $this->assertEquals('New Category Name', $resposeUseCase->name);
        $this->assertEquals($categoryDB->description, $resposeUseCase->description);

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category Name',
            'description' => $categoryDB->description,
            'is_active' => $categoryDB->is_active,
        ]);

    }
}
