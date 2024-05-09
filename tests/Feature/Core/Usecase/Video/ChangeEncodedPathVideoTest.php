<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\Video as VideoModel;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoInputDTO;
use Tests\TestCase;

class ChangeEncodedPathVideoTest extends TestCase
{
    public function testIfUpdatedMediaInDB()
    {
        $video = VideoModel::factory()->create();

        $useCase = new ChangeEncodedPathVideo(
            repository: $this->app->make(VideoRepositoryInterface::class)
        );

        $input = new ChangeEncodedVideoInputDTO(
            id: $video->id,
            encodedPath: 'path-id/video_encoded.ext'
        );

        $useCase->exec($input);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $input->id,
            'encoded_path' => $input->encodedPath,
        ]);

    }
}
