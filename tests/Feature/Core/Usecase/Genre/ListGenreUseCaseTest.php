<?php

namespace Tests\Feature\Core\Usecase\Genre;

use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\{
    GenreEloquentRepository
};
use Core\DTO\Genre\GenreInputDTO;
use Core\DTO\Genre\GenreOutputDTO;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{
    public function testGetGenreById()
    {
        $genreDB = ModelGenre::factory()->create();

        $repository = new GenreEloquentRepository(new ModelGenre());
        $usecase = new ListGenreUseCase($repository);

        $responseUseCase = $usecase->execute(new GenreInputDTO(
            id: $genreDB->id
        )
        );

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
        $this->assertEquals($genreDB->id, $responseUseCase->id);
        $this->assertEquals($genreDB->name, $responseUseCase->name);

    }
}
