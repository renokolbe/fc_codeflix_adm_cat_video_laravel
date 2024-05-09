<?php

namespace Tests\Feature\Core\Usecase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\DTO\Category\ListCategories\ListCategoriesOutputDTO;
use Core\UseCase\Category\ListCategoriesUseCase;
use Tests\TestCase;

class ListCategoriesUseCaseTest extends TestCase
{
    
    public function test_list_empty()
    {
        $useCase = $this->createUseCase();

        $responseUseCase = $useCase->execute(new ListCategoriesInputDTO());
        
        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);

    }
    
    public function test_list_all_categories()
    {
        $categoryDB = ModelCategory::factory()->count(20)->create();
        $useCase = $this->createUseCase();

        $responseUseCase = $useCase->execute(
            new ListCategoriesInputDTO(
            )
        );
        
        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertCount(15, $responseUseCase->items);
        $this->assertEquals(count($categoryDB), $responseUseCase->total);

    }

    private function createUseCase()
    {
        $model = new ModelCategory();
        $repository = new CategoryEloquentRepository($model);
        return new ListCategoriesUseCase($repository);

    }
}
