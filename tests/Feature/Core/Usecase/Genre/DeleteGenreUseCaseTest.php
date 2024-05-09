<?php

namespace Tests\Feature\Core\Usecase\Genre;

use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
{
    public function test_delete_genre()
    {
        $genreDB = ModelGenre::factory()->create();

        $genre = new ModelGenre();
        $repository = new GenreEloquentRepository($genre);
        $useCase = new DeleteGenreUseCase($repository);

        $response = $useCase->execute(
            new GenreInputDTO(
                id: $genreDB->id
            ),
        );

        $this->assertTrue($response->success);
        $this->assertSoftDeleted('genres', [
            'id' => $genreDB->id
        ]);

    }
}
