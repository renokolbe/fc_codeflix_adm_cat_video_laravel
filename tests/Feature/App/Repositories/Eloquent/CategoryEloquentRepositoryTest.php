<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Tests\TestCase;
use Throwable;

class CategoryEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function testInsert()
    {
        $entity = new EntityCategory(
            name: 'Category Name 1',
            description: 'Category Description 1',
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category Name 1',
        ]);
    }

    public function testFindByID()
    {
        $category = Model::factory()->create();
        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id);
    }

    public function testFindByIDNotFound()
    {
        try {
            $this->repository->findById('fakeValue');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testFindAll()
    {
        $categories = Model::factory()->count(10)->create();
        $response = $this->repository->findAll();

        $this->assertCount(count($categories), $response);
    }

    public function testPaginate()
    {
        Model::factory()->count(100)->create();
        $response = $this->repository->paginate();

        //dump($response);

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertEquals(100, $response->total());

    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();

        //dump($response);

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertEquals(0, $response->total());
        $this->assertCount(0, $response->items());

    }

    public function testUpdateIdNotFound()
    {
        try {
            $category = new EntityCategory(name: 'test');
            $this->repository->Update($category);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdate()
    {
        $categoryDB = Model::factory()->create();
        $category = new EntityCategory(
            id: $categoryDB->id,
            name: 'Category Name Updated 1',
            description: 'Category Description Updated 1',
        );
        $response = $this->repository->Update($category);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id);
        $this->assertNotEquals($categoryDB->name, $response->name);
        $this->assertEquals('Category Name Updated 1', $response->name);
        $this->assertEquals('Category Description Updated 1', $response->description);
    }

    public function testDeleteIdNotFound()
    {
        try {
            $this->repository->Delete('fakeValue');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testDelete()
    {
        $categoryDB = Model::factory()->create();
        $response = $this->repository->Delete($categoryDB->id);

        $this->assertTrue($response);
    }
}
