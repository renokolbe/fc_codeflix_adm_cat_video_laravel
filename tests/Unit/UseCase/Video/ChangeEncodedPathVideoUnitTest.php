<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoInputDTO;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChangeEncodedPathVideoUnitTest extends TestCase
{
    public function testChangeEncodedPath()
    {

        $input = new ChangeEncodedVideoInputDTO(
            id: 'id',
            encodedPath: 'path/video_encoded/video.ext'
        );

        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->with($input->id)
            ->times(1)
            ->andReturn($this->getEntity());

        $mockRepository->shouldReceive('updateMedia')
            ->times(1);

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );

        $response = $useCase->exec(input: $input);

        $this->assertInstanceOf(ChangeEncodedVideoOutputDTO::class, $response);
    }

    public function testChangeEncodedPathException()
    {

        $this->expectException(NotFoundException::class);

        $input = new ChangeEncodedVideoInputDTO(
            id: 'id',
            encodedPath: 'path/video_encoded/video.ext'
        );

        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->with($input->id)
            ->times(1)
            ->andThrow(new NotFoundException('Video not found'));

        $mockRepository->shouldReceive('updateMedia')
            ->times(0);

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );

        $response = $useCase->exec(input: $input);
    }

    private function getEntity(): EntityVideo
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

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
