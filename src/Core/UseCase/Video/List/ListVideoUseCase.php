<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\ListInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListOutputVideoDTO;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository
    ) {
    }

    public function execute(ListInputVideoDTO $input): ListOutputVideoDTO
    {
        $entity = $this->videoRepository->findById($input->id);

        return new ListOutputVideoDTO(
            id: $entity->id,
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            createdAt: $entity->createdAt(),
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMembersIds,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path()
        );
    }
}
