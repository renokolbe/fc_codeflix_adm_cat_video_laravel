<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;
use Throwable;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new Model());
    }

    public function testInsert()
    {
        $entity = new EntityGenre(
            name: 'Genre Name 1',
        );

        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->id, $response->id);
        $this->assertEquals($entity->name, $response->name);

        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityGenre::class, $response);
        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
            'name' => 'Genre Name 1',
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new EntityGenre(
            name: 'Genre Name 1',
        );
        $entity->deactivate();

        $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'name' => 'Genre Name 1',
            'is_active' => false,
        ]);
    }

    public function testInsertWithrelationships()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new EntityGenre(
            name: 'Genre Name 1',
        );

        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        //dump($response);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
            'name' => 'Genre Name 1',
        ]);

        $this->assertDatabaseCount('category_genre', count($categories));

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_genre', [
                'genre_id' => $response->id(),
                'category_id' => $category->id,
            ]);
        }

        // $this->assertDatabaseHas('category_genre', [
        //     'genre_id' => $response->id(),
        //     'category_id' => $categories[0]->id,
        // ]);
    }

    public function testFindByID()
    {
        $genre = Model::factory()->create();
        $response = $this->repository->findById($genre->id);

        $this->assertInstanceOf(EntityGenre::class, $response);
        $this->assertEquals($genre->id, $response->id);
    }

    public function testFindByIDNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fakeValue');

        // try {
        //     $this->repository->findById('fakeValue');
        //     $this->assertTrue(false);
        // } catch (Throwable $th) {
        //     $this->assertInstanceOf(NotFoundException::class, $th);
        // }
    }

    public function testFindAll()
    {
        $categories = Model::factory()->count(10)->create();
        $response = $this->repository->findAll();

        $this->assertCount(count($categories), $response);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(5)->create([
            'name' => 'Teste',
        ]);

        Model::factory()->count(10)->create();

        $response = $this->repository->findAll(
            filter: 'Teste'
        );

        $this->assertCount(5, $response);

        $response = $this->repository->findAll();

        $this->assertCount(15, $response);
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

    public function testPaginateWithFilter()
    {
        Model::factory()->count(20)->create([
            'name' => 'Teste',
        ]);
        Model::factory()->count(20)->create();
        $response = $this->repository->paginate(
            filter: 'Teste'
        );

        //dump($response);

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());

    }

    public function testUpdateIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $genre = new EntityGenre(name: 'test');
        $this->repository->Update($genre);
        // try {
        //     $genre = new EntityGenre(name: 'test');
        //     $this->repository->Update($genre);
        //     $this->assertTrue(false);
        // } catch (Throwable $th) {
        //     $this->assertInstanceOf(NotFoundException::class, $th);
        // }
    }

    public function testUpdate()
    {
        $genreDB = Model::factory()->create();
        $genre = new EntityGenre(
            id: new Uuid($genreDB->id),
            name: 'Genre Name Updated 1',
        );
        $response = $this->repository->Update($genre);

        $this->assertInstanceOf(EntityGenre::class, $response);
        $this->assertEquals($genre->id, $response->id);
        $this->assertNotEquals($genreDB->name, $response->name);
        $this->assertEquals('Genre Name Updated 1', $response->name);
    }

    public function testDeleteIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->Delete('fakeValue');
        // try {
        //     $this->repository->Delete('fakeValue');
        //     $this->assertTrue(false);
        // } catch (Throwable $th) {
        //     $this->assertInstanceOf(NotFoundException::class, $th);
        // }
    }

    public function testDelete()
    {
        $genreDB = Model::factory()->create();
        $response = $this->repository->Delete($genreDB->id);

        $this->assertTrue($response);
        $this->assertSoftDeleted('genres', [
            'id' => $genreDB->id,
        ]);
    }
}
