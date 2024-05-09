<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDTO;
use Core\DTO\Genre\UpdateGenre\GenreUpdateOutputDTO;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdateGenreUseCaseUnitTest extends TestCase
{
    public function test_categories_not_found()
    {
        $this->expectException(NotFoundException::class);

        $id = (string) RamseyUuid::uuid4()->toString();
        $genreName = 'Genre 1';

        $categoryId1 = (string) RamseyUuid::uuid4()->toString();

        $useCase = new UpdateGenreUseCase($this->mockGenreRepository(
            $this->mockGenreEntity($id, $genreName, 0), 0),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$categoryId1]));

        $useCase->execute($this->mockGenreUpdateInputDTO($id, $genreName, [$categoryId1, 'fake_id_1', 'fake_id_2']));

        $this->tearDown();
    }

    public function test_update()
    {
        $id = (string) RamseyUuid::uuid4()->toString();
        $genreName = 'Genre 1';

        $categoryId1 = (string) RamseyUuid::uuid4()->toString();
        $categoryId2 = (string) RamseyUuid::uuid4()->toString();

        $useCase = new UpdateGenreUseCase($this->mockGenreRepository(
            $this->mockGenreEntity($id, $genreName)),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$categoryId1, $categoryId2]));

        $response = $useCase->execute($this->mockGenreUpdateInputDTO($id, $genreName, [$categoryId1, $categoryId2]));

        $this->assertInstanceOf(GenreUpdateOutputDTO::class, $response);

        $this->assertIsString($response->id);
        $this->assertEquals($genreName, $response->name);
        $this->assertNotEmpty($response->created_at);

        $this->tearDown();

    }

    private function mockGenreEntity(string $uuid, string $name, $timesCalled = 1)
    {
        $mockEntity = Mockery::mock(Genre::class, [
            $name,
            new Uuid($uuid),
        ]);

        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update')->times($timesCalled);
        $mockEntity->shouldReceive('addCategory');

        return $mockEntity;

    }

    private function mockGenreRepository(Genre $genre, int $timesCalled = 1)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->once()->andReturn($genre);
        $mockRepository->shouldReceive('update')->times($timesCalled)->andReturn($genre);

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
            ->times(1)
            ->andReturn($arrayUuid);

        return $mockCategoryRepository;
    }

    private function mockGenreUpdateInputDTO(string $uuid, string $name, array $arrayUuid = [])
    {
        $mockInputDto = Mockery::mock(GenreUpdateInputDTO::class, [
            $uuid, $name, $arrayUuid,
        ]);

        return $mockInputDto;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
