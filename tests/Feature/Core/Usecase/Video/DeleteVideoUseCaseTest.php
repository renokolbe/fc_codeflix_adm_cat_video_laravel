<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Tests\TestCase;

class DeleteVideoUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $video = Video::factory()->create();

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(
            new DeleteInputVideoDTO($video->id)
        );

        $this->assertTrue($response->success);
        $this->assertSoftDeleted('videos', [
            'id' => $video->id,
        ]);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);
        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $useCase->execute(
            new DeleteInputVideoDTO('fake_id')
        );

    }
}
