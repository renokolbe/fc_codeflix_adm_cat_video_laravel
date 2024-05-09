<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\{
    DTO\ListInputVideoDTO,
    ListVideoUseCase
};
use Tests\TestCase;

class ListVideoUseCaseTest extends TestCase
{
    public function testFind()
    {
        $video = Video::factory()->create();

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(
            new ListInputVideoDTO($video->id)
        );

        $this->assertEquals($video->id, $response->id);
        $this->assertEquals($video->title, $response->title);
        $this->assertEquals($video->description, $response->description);
        $this->assertEquals($video->year_launched, $response->yearLaunched);
        $this->assertEquals($video->rating, $response->rating);
        $this->assertEquals($video->duration, $response->duration);
        $this->assertEquals($video->opened, $response->opened);

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => $video->title,
            'description' => $video->description,
            'year_launched' => $video->year_launched,
            'rating' => $video->rating,
            'duration' => $video->duration,
            'opened' => $video->opened
        ]);

    }
    
    public function testFindNotFound()
    {
        $this->expectException(NotFoundException::class);

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(
            new ListInputVideoDTO('fake_id')
        );
        
    }

}
