<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Category;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
use Core\UseCase\Genre\CreateGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\Genre\CreateGenre\{
    GenreCreateInputDTO,
    GenreCreateOutputDTO
};
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCaseUnitTest extends TestCase
{
    
    public function test_categories_not_found()
    {
        $this->expectException(NotFoundException::class);

        $id = (string) RamseyUuid::uuid4()->toString();
        $genreName = 'Genre 1';

        $categoryId1 = (string) RamseyUuid::uuid4()->toString();

        $useCase = new CreateGenreUseCase($this->mockGenreRepository(
            $this->mockGenreEntity($id, $genreName), 0), 
            $this->mockTransaction(), 
            $this->mockCategoryRepository([$categoryId1]));

        $useCase->execute($this->mockGenreCreateInputDTO($genreName, [$categoryId1, 'fake_id_1', 'fake_id_2'], true));

        $this->tearDown();
    }
    
    public function test_create()
    {
        $id = (string) RamseyUuid::uuid4()->toString();
        $genreName = 'Genre 1';

        $categoryId1 = (string) RamseyUuid::uuid4()->toString();
        $categoryId2 = (string) RamseyUuid::uuid4()->toString();

        $useCase = new CreateGenreUseCase($this->mockGenreRepository(
            $this->mockGenreEntity($id, $genreName)), 
            $this->mockTransaction(), 
            $this->mockCategoryRepository([$categoryId1, $categoryId2]));

        $response = $useCase->execute($this->mockGenreCreateInputDTO($genreName, [$categoryId1, $categoryId2], true));

        $this->assertInstanceOf(GenreCreateOutputDTO::class, $response);

        $this->assertIsString($response->id);
        $this->assertEquals($genreName, $response->name);
        $this->assertNotEmpty($response->created_at);

        $this->tearDown();

    }

    private function mockGenreEntity(string $uuid, string $name)
    {
        $mockEntity =  Mockery::mock(Genre::class, [
            $name,
            new Uuid($uuid),
        ]);
        
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;

    }

    private function mockGenreRepository(Genre $genre, int $timesCalled = 1)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);        
        $mockRepository->shouldReceive('insert')->times($timesCalled)->andReturn($genre);
        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        return $mockTransaction;
    }

    private function mockCategoryRepository(array $arrayUuid = [])
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')
                        ->andReturn($arrayUuid);

        return $mockCategoryRepository;
    }

    private function mockGenreCreateInputDTO(string $name, array $arrayUuid = [], bool $isActive = true)
    {
        $mockInputDto = Mockery::mock(GenreCreateInputDTO::class, [
            $name,$arrayUuid, $isActive,
        ]);

        return $mockInputDto;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
