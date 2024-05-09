<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\DTO\Genre\GenreInputDTO;
use Core\DTO\Genre\List\ListGenresInputDTO;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDTO;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListGenresInputDTO(
            filter: $request->get('filter', '') ?? '',
            order: $request->get('order', 'DESC'),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
            )
        );

        return GenreResource::collection(collect($response->items))
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreGenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreCreateInputDTO(
                name: $request->name,
                isActive: (bool) $request->is_active ?? true,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, ListGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreInputDTO(
                id: $id
            )
        );

        return (new GenreResource($response))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $usecase, string $id)
    {
        $response = $usecase->execute(
            input: new GenreUpdateInputDTO(
                id: $id,
                name: $request->name,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteGenreUseCase $useCase, string $id)
    {
        $useCase->execute(
            input: new GenreInputDTO(
                id: $id
            )
        );

        return response()->noContent();
    }
}
