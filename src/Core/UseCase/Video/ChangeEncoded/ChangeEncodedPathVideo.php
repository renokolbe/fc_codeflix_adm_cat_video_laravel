<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\Domain\Enum\MediaStatus;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Media;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoInputDTO;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoOutputDTO;

class ChangeEncodedPathVideo
{

    public function __construct(
        protected VideoRepositoryInterface $repository
    )
    {}

    public function exec(ChangeEncodedVideoInputDTO $input): ChangeEncodedVideoOutputDTO
    {
        $video = $this->repository->findById($input->id);

        $video->setVideoFile(
            new Media(
                filePath: $video->videoFile()?->filePath ?? '',
                mediaStatus: MediaStatus::COMPLETE,
                encodedPath: $input->encodedPath
            )
        );

        $response = $this->repository->updateMedia($video);

        return new ChangeEncodedVideoOutputDTO(
            id: $video->id(),
            encodedPath: $input->encodedPath
        );
    }
}