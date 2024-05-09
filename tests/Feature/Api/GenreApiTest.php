<?php

namespace Tests\Feature\Api;

use App\Models\Genre as ModelGenre;
use App\Models\Category as ModelCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class GenreApiTest extends TestCase
{
    use WithoutMiddlewareTrait;
    protected $endpoint = '/api/genres';

    public function testIndexEmpty()
    {
        $response = $this->getJson($this->endpoint);

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testIndex()
    {
        ModelGenre::factory(20)->create();
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
            'name' => 'Ge',
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

    public function testStoreValidationInvalidCategories()
    {

        $data = [
            'name' => 'Genre criado pela API',
            'is_active' => true,
            'categories_ids' => ['fake_id_1', 'fake_id_2'],
        ];

        $response = $this->postJson($this->endpoint, $data);
 
        //$response->dump();

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories_ids',
            ]
        ]);
        $this->assertArrayHasKey('categories_ids', $response->json('errors'));
        $this->assertEquals('The selected categories ids is invalid.', $response->json('errors.categories_ids')[0]);
    }

    public function testStore()
    {

        $categoriesDb = ModelCategory::factory(3)->create();

        $data = [
            'name' => 'Genre criado pela API',
            'is_active' => true,
      //            'categoriesId' => [$categoriesDb[0]->id, $categoriesDb[1]->id, $categoriesDb[2]->id],
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];

        $response = $this->postJson($this->endpoint, $data);
 
        //$response->dump();
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
            ]
         ]);
        
         $response2 = $this->postJson($this->endpoint, [
            'name' => 'Genre Name 2 Test from API',
            'is_active' => false,
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ]);
 
        //$response2->dump();
        $response2->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('Genre Name 2 Test from API', $response2->json('data')['name']);
        $this->assertEquals(false, $response2['data']['is_active']);
        $this->assertDatabaseHas('genres', [
            'id' => $response2['data']['id'],
            'name' => 'Genre Name 2 Test from API',
            'is_active' => false,
        ]);
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("$this->endpoint/0");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShow()
    {
        $genre = ModelGenre::factory()->create();
        $response = $this->getJson($this->endpoint . "/{$genre->id}");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
            ]
        ]);
        $this->assertEquals($genre->id, $response->json('data.id'));
        $this->assertEquals($genre->name, $response->json('data.name'));
        $this->assertEquals($genre->is_active, $response->json('data.is_active'));
    }

    public function testUpdateNotFound()
    {
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => 'Genre Test from API',
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];

        $response = $this->putJson("{$this->endpoint}/0", $data);

        //$response->dump();

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Genre Not Found', $response->json('message'));

    }

    public function testUpdateValidationNameEmpty()
    {
        $genreDb = ModelGenre::factory()->create();
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => '',
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
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
        $genreDb = ModelGenre::factory()->create();
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => 'Ge',
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
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
        $genreDb = ModelGenre::factory()->create();
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => str_repeat('a', 256),
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
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

    public function testUpdateValidationCategoriesEmpty()
    {
        $genreDb = ModelGenre::factory()->create();
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => 'Genre Test from API',
            'categories_ids' => [],
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories_ids',
            ]
        ]);
        $this->assertArrayHasKey('categories_ids', $response->json('errors'));
        $this->assertEquals('The categories ids field is required.', $response->json('errors.categories_ids')[0]);
    }

    public function testUpdateValidationInvalidCategories()
    {
        $genreDb = ModelGenre::factory()->create();
        $data = [
            'name' => 'Genre Test from API',
            'categories_ids' => ['fake_id'],
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories_ids',
            ]
        ]);
        $this->assertArrayHasKey('categories_ids', $response->json('errors'));
        $this->assertEquals('The selected categories ids is invalid.', $response->json('errors.categories_ids')[0]);
    }

    public function testUpdate()
    {
        $genreDb = ModelGenre::factory()->create();
        $categoriesDb = ModelCategory::factory(3)->create();
        $data = [
            'name' => 'Genre Test from API',
            'categories_ids' => $categoriesDb->pluck('id')->toArray(),
        ];
        $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
            ]
        ]);
        $this->assertEquals('Genre Test from API', $response->json('data')['name']);
        $this->assertEquals(true, $response->json('data')['is_active']);
        $this->assertDatabaseHas('genres', [
            'id' => $response->json('data')['id'],
            'name' => 'Genre Test from API',
            'is_active' => true,
        ]);
    }

    public function testDestroyNotFound()
    {
        $response = $this->deleteJson("{$this->endpoint}/{fake_id}");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDestroy()
    {
        $genreDb = ModelGenre::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$genreDb->id}");
        //$response->dump();
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('genres', [
            'id' => $genreDb->id,
        ]);
    }
}
