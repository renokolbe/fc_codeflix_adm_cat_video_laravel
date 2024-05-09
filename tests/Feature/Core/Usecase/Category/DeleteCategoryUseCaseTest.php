<?php

namespace Tests\Feature\Core\Usecase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $categoryDB = ModelCategory::factory()->create();

        $model = new ModelCategory();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new DeleteCategoryUseCase($repository);

        $useCase->execute(
            new CategoryInputDTO(
                id: $categoryDB->id
            )
        );

        $this->assertSoftDeleted('categories', [
            'id' => $categoryDB->id
        ]);

    }
}
