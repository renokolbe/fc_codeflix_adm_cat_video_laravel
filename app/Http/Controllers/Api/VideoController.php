<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
//use App\Http\Resources\VideoResource;
use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListInputVideoDTO;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\Paginate\{
    DTO\PaginateInputVideoDTO,
    ListVideosUseCase
};
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $useCase)
    {

        $response = $useCase->execute(
            input: new PaginateInputVideoDTO(
             filter: $request->get('filter', '') ?? '',
             order: $request->get('order', 'DESC'),
             page: (int) $request->get('page', 1),
             totalPage: (int) $request->get('totalPage', 15),
             ));

        //dd($response);

        // return VideoResource::collection(collect($response->items()))
        //     ->additional([
        //         'meta' => [
        //             'total' => $response->total(),
        //             'current_page' => $response->currentPage(),
        //             'last_page' => $response->lastPage(),
        //             'first_page' => $response->firstPage(),
        //             'to' => $response->to(),
        //             'from' => $response->from(),
        //             'per_page' => $response->perPage()
        //         ]
        //     ]);

        return (new ApiAdapter($response))
                ->toJson();

    }

    public function show(ListVideoUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new ListInputVideoDTO($id));

        //return (new VideoResource($response))->response();
        return ApiAdapter::json($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
    {


        $response = $useCase->exec(
            input: new CreateInputVideoDTO(
                title: $request->title,
                description: $request->description,
                yearLaunched: $request->year_launched,
                duration: $request->duration,
                opened: $request->opened,
                rating: Rating::from($request->rating),
                categories: $request->categories,
                genres: $request->genres,
                castMembers: $request->cast_members,
                videoFile: getArrayFile($request->file('video_file')),
                trailerFile: getArrayFile($request->file('trailer_file')),
                bannerFile: getArrayFile($request->file('banner_file')),
                thumbFile: getArrayFile($request->file('thumb_file')),
                thumbHalf: getArrayFile($request->file('thumb_half_file'))
            )
        );

        // return (new VideoResource($response))
        //         ->response()
        //         ->setStatusCode(Response::HTTP_CREATED)
        // ;
        return ApiAdapter::json($response, Response::HTTP_CREATED);
        
    }

    public function update(UpdateVideoUseCase $useCase, string $id, UpdateVideoRequest $request)
    {

        $response = $useCase->exec(
            input: new UpdateInputVideoDTO(
                id: $id,
                title: $request->title,
                description: $request->description,
                categories: $request->categories,
                genres: $request->genres,
                castMembers: $request->cast_members,
                videoFile: getArrayFile($request->file('video_file')),
                trailerFile: getArrayFile($request->file('trailer_file')),
                bannerFile: getArrayFile($request->file('banner_file')),
                thumbFile: getArrayFile($request->file('thumb_file')),
                thumbHalf: getArrayFile($request->file('thumb_half_file'))
            )
        );

        // return (new VideoResource($response))
        //         ->response()
        //         ->setStatusCode(Response::HTTP_OK)
        // ;

        return ApiAdapter::json($response);
        
    }

    public function destroy(DeleteVideoUseCase $useCase, string $id)
    {

        $useCase->execute(
            input: new DeleteInputVideoDTO(
                id: $id
            )
        );

        return response()->noContent();
    }
}
