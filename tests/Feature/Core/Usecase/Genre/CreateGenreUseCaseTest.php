<?php

namespace Tests\Feature\Core\Usecase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function testInsertGenre()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $usecase->execute(
            new GenreCreateInputDTO(
                name: 'test',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'test',
        ]);

        $this->assertDatabaseCount('category_genre', 10);

    }

    public function testInsertGenresWithInvalidCategories()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        $usecase->execute(
            new GenreCreateInputDTO(
                name: 'test',
                categoriesId: $categoriesIds
            )
        );

    }

    public function testTransactionsOk()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $usecase->execute(
                new GenreCreateInputDTO(
                    name: 'test',
                    categoriesId: $categoriesIds
                )
            );
            $this->assertDatabaseHas('genres', [
                'name' => 'test',
            ]);
            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }

    public function testTransactionsNOk()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $usecase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory,
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        try {
            $usecase->execute(
                new GenreCreateInputDTO(
                    name: 'test',
                    categoriesId: $categoriesIds
                )
            );
            $this->assertDatabaseHas('genres', [
                'name' => 'test',
            ]);
            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }
}
