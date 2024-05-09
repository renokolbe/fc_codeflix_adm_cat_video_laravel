<?php

namespace Core\UseCase\Video\Delete;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Core\UseCase\Video\Delete\DTO\DeleteOutputVideoDTO;

class DeleteVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository
    ) {
    }

    public function execute(DeleteInputVideoDTO $input): DeleteOutputVideoDTO
    {
        return new DeleteOutputVideoDTO(
            success: $this->videoRepository->delete($input->id)
        );
    }
}
