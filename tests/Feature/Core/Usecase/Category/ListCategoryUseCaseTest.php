<?php

namespace Tests\Feature\Core\Usecase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\CategoryOutputDTO;
use Core\UseCase\Category\ListCategoryUseCase;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_find()
    {
        $categoryDB = ModelCategory::factory()->create();
        $model = new ModelCategory();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new ListCategoryUseCase($repository);

        $responseUseCase = $useCase->execute(
            new CategoryInputDTO(
                id: $categoryDB->id
            )
        );

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
        $this->assertEquals($categoryDB->id, $responseUseCase->id);
        $this->assertEquals($categoryDB->name, $responseUseCase->name);
        $this->assertEquals($categoryDB->description, $responseUseCase->description);
    }
}
