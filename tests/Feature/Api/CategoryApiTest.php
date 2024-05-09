<?php

namespace Tests\Feature\Api;

use App\Models\Category as CategoryModel;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class CategoryApiTest extends TestCase
{
    use WithoutMiddlewareTrait;

    protected $endpoint = '/api/categories';

    // Substituido pela Trait WithoutMiddleware
    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Desabilitar o middleware de Autenticacao
    //     $this->withoutMiddleware([
    //         \App\Http\Middleware\Authenticate::class,
    //         \Illuminate\Auth\Middleware\Authorize::class,
    //     ]);
    // }

    public function test_list_empty_categories()
    {
        $response = $this->getJson($this->endpoint);

        //$response->dump();

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all_categories()
    {
        CategoryModel::factory()->count(30)->create();
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        //$response->dump();

        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ],
        ]);

        $response->assertJsonCount(15, 'data');

    }

    public function test_list_paginate_categories()
    {
        CategoryModel::factory()->count(25)->create();
        $response = $this->getJson("$this->endpoint?page=2");

        //$response->dump();

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('meta.current_page'));
        $this->assertEquals(25, $response['meta']['total']);
        $response->assertJsonCount(10, 'data');
    }

    public function test_list_category_notfound()
    {
        $response = $this->getJson("$this->endpoint/123");
        //$response->dump();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_list_category()
    {
        $categoryDb = CategoryModel::factory()->create();

        $response = $this->getJson("$this->endpoint/{$categoryDb->id}");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ],
        ]);
        $this->assertEquals($categoryDb->id, $response->json('data.id'));
    }

    public function test_validation_name_empty_store()
    {
        $data = [];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name field is required.', $response->json('errors.name')[0]);

    }

    public function test_validation_name_tooshort_store()
    {
        $data = [
            'name' => 'a',
        ];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must be at least 3 characters.', $response->json('errors.name')[0]);

    }

    public function test_validation_name_tooslong_store()
    {
        $data = [
            'name' => str_repeat('a', 256),
        ];
        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json('errors.name')[0]);

    }

    public function test_valid_store()
    {
        $data = [
            'name' => 'Category Test from API',
        ];

        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ],
        ]);

        $response2 = $this->postJson($this->endpoint, [
            'name' => 'Category Name 2 Test from API',
            'description' => 'Category Description 2 Test from API',
            'is_active' => false,
        ]);

        //$response2->dump();
        $response2->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('Category Name 2 Test from API', $response2->json('data')['name']);
        $this->assertEquals('Category Description 2 Test from API', $response2['data']['description']);
        $this->assertEquals(false, $response2['data']['is_active']);
        $this->assertDatabaseHas('categories', [
            'id' => $response2['data']['id'],
            'name' => 'Category Name 2 Test from API',
            'description' => 'Category Description 2 Test from API',
            'is_active' => false,
        ]);

    }

    public function test_notfound_update()
    {
        $data = [
            'name' => 'Category Test from API',
        ];
        $response = $this->putJson("{$this->endpoint}/{fake_id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Category Not Found', $response->json('message'));
    }

    public function test_validations_update()
    {
        $categoryDb = CategoryModel::factory()->create();

        $data = [
            'name' => 'a',
        ];

        $response = $this->putJson("{$this->endpoint}/{$categoryDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertEquals('The name must be at least 3 characters.', $response->json('errors.name')[0]);

        $response2 = $this->putJson("{$this->endpoint}/{$categoryDb->id}", [
            'name' => $categoryDb->name,
            'description' => 'a',
        ]);

        //$response2->dump();
        $response2->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey('description', $response2->json('errors'));
        $this->assertEquals('The description must be at least 3 characters.', $response2->json('errors.description')[0]);
    }

    public function test_update()
    {
        $categoryDb = CategoryModel::factory()->create();

        $data = [
            'name' => 'Category Updated from API',
            'description' => $categoryDb->description,
            'is_active' => $categoryDb->is_active,
        ];

        $response = $this->putJson("{$this->endpoint}/{$categoryDb->id}", $data);
        //$response->dump();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ],
        ]);
        $this->assertEquals('Category Updated from API', $response->json('data')['name']);
        $this->assertDatabaseHas('categories', [
            'id' => $categoryDb->id,
            'name' => 'Category Updated from API',
            'description' => $categoryDb->description,
            'is_active' => $categoryDb->is_active,
        ]);
    }

    public function test_notfound_destroy()
    {
        $response = $this->deleteJson("{$this->endpoint}/{fake_id}");
        //$response->dump();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Category Not Found', $response->json('message'));
    }

    public function test_destroy()
    {
        $categoryDb = CategoryModel::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$categoryDb->id}");
        //$response->dump();
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categories', [
            'id' => $categoryDb->id,
        ]);
    }
}
