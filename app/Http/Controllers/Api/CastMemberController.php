<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\Create\CastMemberCreateInputDTO;
use Core\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\DTO\CastMember\Update\CastMemberUpdateInputDTO;
use Core\UseCase\CastMember\{
    CreateCastMemberUseCase,
    DeleteCastMemberUseCase,
    ListCastMembersUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase
};
use App\Http\Resources\CastMemberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(Request $request, ListCastMembersUseCase $useCase)
    {
        $response = $useCase->execute(new ListCastMembersInputDTO(
            filter: $request->get('filter', '') ?? '',
            order: $request->get('order', 'DESC'),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        ));
        
        return CastMemberResource::collection(collect($response->items))
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

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(input: new CastMemberCreateInputDTO(
            name: $request->name,
            type: (int) $request->type
        ));

        return (new CastMemberResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCastMemberUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new CastMemberInputDTO(
            id: $id
        ));

        return (new CastMemberResource($response))->response();

    }

    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, string $id)
    {
        $response = $useCase->execute(input: new CastMemberUpdateInputDTO(
            id: $id,
            name: $request->name,
        ));

        return (new CastMemberResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCastMemberUseCase $useCase, string $id)
    {
        $useCase->execute(input: new CastMemberInputDTO(
            id: $id,
        ));

        return response()->noContent();
    }

}
