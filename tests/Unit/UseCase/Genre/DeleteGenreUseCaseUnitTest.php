<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\DeleteGenre\GenreDeleteOutputDTO;
use Core\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function testeDelete()
    {
        $id = (string) RamseyUuid::uuid4()->toString();
        // arrange
        $this->mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        // Expect
        $this->mockRepo->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $this->mockInputDto = Mockery::mock(GenreInputDTO::class, [$id]);

        $useCase = new DeleteGenreUseCase($this->mockRepo);

        // action
        $responseUseCase = $useCase->execute($this->mockInputDto);

        // assert
        $this->assertInstanceOf(GenreDeleteOutputDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

        $this->tearDown();
    }

    public function testeDeleteNotFound()
    {
        $id = (string) RamseyUuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')
            ->times(1)
            ->with($id)
            ->andReturn(false);

        $this->mockInputDto = Mockery::mock(GenreInputDTO::class, [$id]);

        $useCase = new DeleteGenreUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(GenreDeleteOutputDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
