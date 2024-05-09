<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\Video;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\UpdateVideoUseCase;

class UpdateVideoUseCaseTest extends BaseVideoUseCase
{
    function useCase(): string
    {
        return UpdateVideoUseCase::class;
    }
    
    function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ? array $videoFile = null,
        ? array $trailerFile = null,
        ? array $bannerFile = null,
        ? array $thumbFile = null,
        ? array $thumbHalf = null,

    ): object
    {
        $video = Video::factory()->create();

        return new UpdateInputVideoDTO(
            id: new Uuid($video->id),
            title: 'New Title Updated',
            description: 'New Description',
            categories: $categories,
            genres: $genres,
            castMembers: $castMembers,
            videoFile: $videoFile,
            trailerFile: $trailerFile,
            bannerFile: $bannerFile,
            thumbFile: $thumbFile,
            thumbHalf: $thumbHalf
        );
    }

}