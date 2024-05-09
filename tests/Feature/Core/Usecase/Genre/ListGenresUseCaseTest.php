<?php

namespace Tests\Feature\Core\Usecase\Genre;

use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\DTO\Genre\List\ListGenresInputDTO;
use Core\DTO\Genre\List\ListGenresOutputDTO;
use Core\UseCase\Genre\ListGenresUseCase;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    public function testListGenres_Empty()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());

        $usecase = new ListGenresUseCase(
            $repository,
        );

        $responseUseCase = $usecase->execute(new ListGenresInputDTO());

        $this->assertInstanceOf(ListGenresOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);

    }

    public function testListGenres_All()
    {
        $repository = new GenreEloquentRepository(new ModelGenre());

        $genreDB = ModelGenre::factory()->count(100)->create();

        $usecase = new ListGenresUseCase(
            $repository,
        );

        $responseUseCase = $usecase->execute(new ListGenresInputDTO());

        $this->assertInstanceOf(ListGenresOutputDTO::class, $responseUseCase);
        $this->assertCount(15, $responseUseCase->items);
        $this->assertEquals(count($genreDB), $responseUseCase->total);

    }
}
