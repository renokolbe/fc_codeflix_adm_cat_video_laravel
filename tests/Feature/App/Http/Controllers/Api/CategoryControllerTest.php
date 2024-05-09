<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $repository;

    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(
            new ModelCategory()
        );

        $this->controller = new CategoryController();

        parent::setUp();
    }

    public function test_index()
    {
        $useCase = new ListCategoriesUseCase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);

        // dump($response);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
        $this->assertIsObject($response->resource);
    }

    public function test_store()
    {
        $useCase = new CreateCategoryUseCase($this->repository);

        $request = new StoreCategoryRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Category Name Test',
        ]));

        //dump($request);
        $response = $this->controller->store($request, $useCase);

        //dump($response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $category = ModelCategory::factory()->create();

        $response = $this->controller->show(
            useCase: new ListCategoryUseCase($this->repository),
            id : $category->id,
        );

        //dump($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update()
    {
        $useCase = new UpdateCategoryUseCase($this->repository);

        $category = ModelCategory::factory()->create();

        $request = new UpdateCategoryRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Category Name Test Updated',
        ]));

        //dump($request);
        $response = $this->controller->update(
            request: $request,
            useCase: $useCase,
            id : $category->id,
        );

        //dump($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Category Name Test Updated',
        ]);
    }

    public function test_delete()
    {
        $useCase = new DeleteCategoryUseCase($this->repository);

        $category = ModelCategory::factory()->create();

        $response = $this->controller->destroy(
            useCase: $useCase,
            id : $category->id,
        );

        //dump($response);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
