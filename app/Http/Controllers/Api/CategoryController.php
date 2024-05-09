<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Core\DTO\Category\ListCategories\ListCategoriesInputDTO;
use App\Http\Resources\CategoryResource;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psy\Util\Str;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(new ListCategoriesInputDTO(
            filter: $request->get('filter', '') ?? '',
            order: $request->get('order', 'DESC'),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        ));
        
        return CategoryResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'to' => $response->to,
                    'from' => $response->from,
                    'per_page' => $response->per_page
                ]
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(input: new CategoryCreateInputDTO(
            name: $request->name,
            description: $request->description ?? '',
            isActive: (bool) $request->isActive ?? true,
        ));

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new CategoryInputDTO(
            id: $id
        ));

        return (new CategoryResource($response))->response();

    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(input: new CategoryUpdateInputDTO(
            id: $id,
            name: $request->name,
            description: $request->description ?? '',
            isActive: (bool) $request->isActive ?? true,
        ));

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCategoryUseCase $useCase, string $id)
    {
        $useCase->execute(input: new CategoryInputDTO(
            id: $id,
        ));

        return response()->noContent();
    }

}
