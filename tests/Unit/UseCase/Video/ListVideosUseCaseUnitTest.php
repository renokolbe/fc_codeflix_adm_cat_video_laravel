<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\UseCase\Video\Paginate\DTO\{
    PaginateInputVideoDTO,
//    PaginateOutputVideoDTO
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListVideosUseCaseUnitTest extends TestCase
{

    use UseCaseTrait;

    public function testEmptyPaginate()
    {

        $mockPagination = $this->mockPagination();

        $mockRepo = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);

        $useCase = new ListVideosUseCase($mockRepo);
        $responseUseCase = $useCase->execute($this->createInputDTO());

        //$this->assertInstanceOf(PaginateOutputVideoDTO::class, $responseUseCase);
        $this->assertInstanceOf(PaginationInterface::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items());

        $this->tearDown();
    }

    public function testListPaginate()
    {

        $video = $this->createEntity();

        $mockPagination = $this->mockPagination(
            [$video],
        );

        $mockRepo = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->times(1)->andReturn($mockPagination);
                
        $useCase = new ListVideosUseCase($mockRepo);
        $responseUseCase = $useCase->execute($this->createInputDTO());

        //$this->assertInstanceOf(PaginateOutputVideoDTO::class, $responseUseCase);
        $this->assertInstanceOf(PaginationInterface::class, $responseUseCase);

        //$this->assertInstanceOf(EntityVideo::class, $responseUseCase->items[0]);
        $this->assertCount(1, $responseUseCase->items());

        $this->tearDown();
    }

    private function createEntity(): EntityVideo
    {
        return new EntityVideo(
            title: 'Video title',
            description: 'Video description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::L
        );
    }

    private function createInputDTO() 
    {
        return new PaginateInputVideoDTO(
            filter: '',
            order: 'DESC',
            page: 1,
            totalPage: 15
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}