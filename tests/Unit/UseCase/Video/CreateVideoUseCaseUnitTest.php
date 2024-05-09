<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Create\DTO\CreateOutputVideoDTO;
use Mockery;

class CreateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function testExecInputOutput()
    {
        $this->createUseCase();

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(CreateOutputVideoDTO::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'insert';
    }

    protected function getUseCase(): string
    {
        return CreateVideoUseCase::class;
    }

    protected function createMockInputDTO(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    ) {
        return Mockery::mock(CreateInputVideoDTO::class, [
            'Video title',
            'Video description',
            2020,
            120,
            true,
            Rating::L,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }
}
