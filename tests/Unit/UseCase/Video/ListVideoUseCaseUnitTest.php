<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\List\DTO\ListInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListOutputVideoDTO;
use Core\UseCase\Video\List\ListVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $uuid = Uuid::random();

        $entity = $this->createEntity($uuid);

        $useCase = new ListVideoUseCase($this->mockRepository($entity));
        $responseUseCase = $useCase->execute($this->mockInputDTO($uuid));

        //$this->assertTrue(true);
        $this->assertInstanceOf(ListOutputVideoDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals($entity->title, $responseUseCase->title);
        $this->assertEquals($entity->description, $responseUseCase->description);
        $this->assertEquals($entity->yearLaunched, $responseUseCase->yearLaunched);
        $this->assertEquals($entity->duration, $responseUseCase->duration);
        $this->assertEquals($entity->opened, $responseUseCase->opened);
        $this->assertEquals($entity->rating, $responseUseCase->rating);

        $this->tearDown();
    }

    private function mockInputDTO(string $id)
    {

        return Mockery::mock(ListInputVideoDTO::class, [$id]);
    }

    private function mockRepository(object $entity)
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->with($entity->id())
            ->times(1)
            ->andReturn($entity);

        return $mockRepository;
    }

    private function createEntity(string $id): EntityVideo
    {

        return new EntityVideo(
            id: new Uuid($id),
            title: 'Video title',
            description: 'Video description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::L
        );

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
