<?php

namespace Tests\Feature\Core\Usecase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\{
    CategoryEloquentRepository,
    GenreEloquentRepository
};
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDTO;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{
    public function testUpdateGenre()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $genre = ModelGenre::factory()->create();

        $categories = ModelCategory::factory()->count(5)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $usecase->execute(
            new GenreUpdateInputDTO(
                id: $genre->id,
                name: 'test updated',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'test updated'
        ]);

        $this->assertDatabaseCount('category_genre', 5);

    }

    public function testUpdateGenresWithInvalidCategories()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $genre = ModelGenre::factory()->create();

        $categories = ModelCategory::factory()->count(5)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        $usecase->execute(
            new GenreUpdateInputDTO(
                id: $genre->id,
                name: 'test updated',
                categoriesId: $categoriesIds
            )
        );

    }

    public function testUpdateTransactionsOk()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $genre = ModelGenre::factory()->create();

        $categories = ModelCategory::factory()->count(5)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $usecase->execute(
                new GenreUpdateInputDTO(
                    id: $genre->id,
                    name: 'test updated',
                    categoriesId: $categoriesIds
                )
            );
                $this->assertDatabaseHas('genres', [
                'name' => 'test updated'
            ]);
            $this->assertDatabaseCount('category_genre', 5);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }

    public function testUpdateTransactionsNOk()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $genre = ModelGenre::factory()->create();

        $categories = ModelCategory::factory()->count(5)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        try {
            $usecase->execute(
                new GenreUpdateInputDTO(
                    id: $genre->id,
                    name: 'test updated',
                    categoriesId: $categoriesIds
                )
            );
                $this->assertDatabaseHas('genres', [
                'name' => 'test updated'
            ]);
            $this->assertDatabaseCount('category_genre', 5);
        } catch (\Throwable $th) {
            //dump($th);
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }    
}
