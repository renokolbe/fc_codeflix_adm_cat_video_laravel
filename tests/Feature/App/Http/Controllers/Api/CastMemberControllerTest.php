<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Requests\{
    StoreCastMemberRequest,
    UpdateCastMemberRequest
};
use App\Http\Controllers\Api\CastMemberController;
use App\Http\Resources\CastMemberResource;
use Core\UseCase\CastMember\{
    ListCastMembersUseCase,
    CreateCastMemberUseCase,
    DeleteCastMemberUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase
};
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use App\Models\CastMember as ModelCastMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{

    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CastMemberEloquentRepository(
            new ModelCastMember()
        );

        $this->controller = new CastMemberController();

        parent::setUp();
    }
    public function test_index()
    {
        $useCase = new ListCastMembersUseCase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);

        // dump($response);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
        $this->assertIsObject($response->resource);
    }

    public function test_store()
    {
        $useCase = new CreateCastMemberUseCase($this->repository);

        $request = new StoreCastMemberRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'CastMember Actor Name Test',
            'type' => 2
        ]));

        //dump($request);
        $response = $this->controller->store($request, $useCase);

        //dump($response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $castMember = ModelCastMember::factory()->create();

        $response = $this->controller->show(
            useCase: new ListCastMemberUseCase($this->repository),
            id : $castMember->id, 
        );

        //dump($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update()
    {
        $useCase = new UpdateCastMemberUseCase($this->repository);

        $castMember = ModelCastMember::factory()->create();

        $request = new UpdateCastMemberRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'CastMember Actor Name Test Updated',
            'type' => 2
        ]));

        //dump($request);
        $response = $this->controller->update(
            request: $request, 
            useCase: $useCase,
            id : $castMember->id, 
        );

        //dump($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertDatabaseHas('cast_members', [
            'id' => $castMember->id,
            'name' => 'CastMember Actor Name Test Updated',
        ]);
    }

    public function test_delete()
    {
        $useCase = new DeleteCastMemberUseCase($this->repository);

        $castMember = ModelCastMember::factory()->create();
        
        $response = $this->controller->destroy(
            useCase: $useCase,
            id : $castMember->id, 
        );

        //dump($response);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }

}
