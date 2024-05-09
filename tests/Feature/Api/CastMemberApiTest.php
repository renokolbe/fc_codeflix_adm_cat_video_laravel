<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\CastMember as ModelCastMember;
use Tests\Traits\WithoutMiddlewareTrait;

class CastMemberApiTest extends TestCase
{
    use WithoutMiddlewareTrait;
    private $endpoint = '/api/cast_members';
    
    public function testIndexEmpty()
    {
        $response = $this->getJson($this->endpoint);

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testIndex()
    {
        ModelCastMember::factory(20)->create();
        $response = $this->getJson($this->endpoint);

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
    }

    public function testIndexPage2()
    {
        ModelCastMember::factory(20)->create();
        $response = $this->getJson("$this->endpoint?page=2");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
        $this->assertEquals(20, $response->json('meta.total'));
        $this->assertEquals(2, $response->json('meta.current_page'));
    }

    public function testIndexWithFilter()
    {
        ModelCastMember::factory(10)->create();
        ModelCastMember::factory(20)->create([
                'name' => 'teste'
        ]);
        $response = $this->getJson("$this->endpoint?filter=teste");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
        $this->assertEquals(20, $response->json('meta.total'));
    }

    public function testIndexWithFilterPage2()
    {
        ModelCastMember::factory(10)->create();
        ModelCastMember::factory(20)->create([
                'name' => 'teste'
        ]);
        $response = $this->getJson("$this->endpoint?filter=teste&page=2");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
        $this->assertEquals(20, $response->json('meta.total'));
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("$this->endpoint/0");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShow()
    {
        $castMember = ModelCastMember::factory()->create();
        $response = $this->getJson($this->endpoint . "/{$castMember->id}");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ]
        ]);
        $this->assertEquals($castMember->id, $response->json('data.id'));
        $this->assertEquals($castMember->name, $response->json('data.name'));
        $this->assertEquals($castMember->type->value, $response->json('data.type'));
    }

    public function testStoreValidationNameEmpty()
    {
        $data = [];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name field is required.', $response->json('errors.name')[0]);
    }

    public function testStoreValidationNameTooShort()
    {
        $data = [
            'name' => 'Na',
        ];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must be at least 3 characters.', $response->json('errors.name')[0]);
    }

    public function testStoreValidationNameTooLong()
    {
        $data = [
            'name' =>  str_repeat('a', 256),
        ];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json('errors.name')[0]);
    }
    public function testStore()
    {

        $data = [
            'name' => 'Actor criado pela API',
            'type' => 2
        ];

        $response = $this->postJson($this->endpoint, $data);
 
        //$response->dump();
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ]
         ]);
         $this->assertDatabaseHas('cast_members', [
            'id' => $response['data']['id'],
            'name' => 'Actor criado pela API',
            'type' => 2,
        ]);
        
         $response2 = $this->postJson($this->endpoint, [
            'name' => 'Director criado pela API',
            'type' => 1
        ]);
 
        //$response2->dump();
        $response2->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('Director criado pela API', $response2->json('data')['name']);
        $this->assertEquals(1, $response2->json('data')['type']);
        $this->assertDatabaseHas('cast_members', [
            'id' => $response2['data']['id'],
            'name' => 'Director criado pela API',
            'type' => 1,
        ]);
    }
    public function testUpdateNotFound()
    {
        $data = [
            'name' => 'Actor criado pela API',
            'type' => 2
        ];

        $response = $this->putJson("{$this->endpoint}/0", $data);

        //$response->dump();

        $response->assertStatus(Response::HTTP_NOT_FOUND);

    }

    public function testUpdateValidationNameEmpty()
    {
        $castMemberDb = ModelCastMember::factory()->create();
        $data = [
            'name' => '',
        ];
        $response = $this->putJson("{$this->endpoint}/{$castMemberDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name field is required.', $response->json('errors.name')[0]);
    }

    public function testUpdateValidationNameTooShort()
    {
        $castMemberDb = ModelCastMember::factory()->create();
        $data = [
            'name' => 'Ge',
        ];
        $response = $this->putJson("{$this->endpoint}/{$castMemberDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must be at least 3 characters.', $response->json('errors.name')[0]);
    }

    public function testUpdateValidationNameTooLong()
    {
        $castMemberDb = ModelCastMember::factory()->create();
        $data = [
            'name' => str_repeat('a', 256),
        ];
        $response = $this->putJson("{$this->endpoint}/{$castMemberDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ]
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json('errors.name')[0]);
    }

    public function testUpdate()
    {
        $castMemberDb = ModelCastMember::factory()->create();
        $data = [
            'name' => 'Actor criado pela API',
            'type' => 2
        ];
        $response = $this->putJson("{$this->endpoint}/{$castMemberDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ]
        ]);
        $this->assertEquals('Actor criado pela API', $response->json('data')['name']);
        $this->assertDatabaseHas('cast_members', [
            'id' => $response->json('data')['id'],
            'name' => 'Actor criado pela API',
        ]);
    }

    public function testDestroyNotFound()
    {
        $response = $this->deleteJson("{$this->endpoint}/{fake_id}");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDestroy()
    {
        $castMemberDb = ModelCastMember::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$castMemberDb->id}");
        //$response->dump();
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('cast_members', [
            'id' => $castMemberDb->id,
        ]);
    }

}
