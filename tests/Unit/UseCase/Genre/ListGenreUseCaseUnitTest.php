<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\Genre\GenreInputDTO;
use Core\DTO\Genre\GenreOutputDTO;
use Core\UseCase\Genre\ListGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class ListGenreUseCaseUnitTest extends TestCase
{
    
    public function testeGetById()
    {
        $id = (string) RamseyUuid::uuid4()->toString();
        $genreName = 'Genre 1';

        $this->mockEntity = Mockery::mock(Genre::class, [
            $genreName,
            new Uuid($id),
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
                        ->once()
                        ->with($id)
                        ->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(GenreInputDTO::class, [
            $id,
        ]);
                        
        $useCase = new ListGenreUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
        $this->assertEquals($id, $responseUseCase->id);
        $this->assertEquals($genreName, $responseUseCase->name);
        $this->assertEquals(true, $responseUseCase->is_active);

        Mockery::close();
    }
}
