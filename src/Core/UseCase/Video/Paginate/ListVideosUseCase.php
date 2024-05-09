<?php

namespace Core\UseCase\Video\Paginate;

use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\PaginateInputVideoDTO;
use Core\UseCase\Video\Paginate\DTO\PaginateOutputVideoDTO;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository
    ) {
    }

    public function execute(PaginateInputVideoDTO $input): PaginationInterface
    {
        return $this->videoRepository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        // return new PaginateOutputVideoDTO(
        //     items: $videos->items(),
        //     total: $videos->total(),
        //     last_page: $videos->lastPage(),
        //     first_page: $videos->firstPage(),
        //     current_page: $videos->currentPage(),
        //     per_page: $videos->perPage(),
        //     to: $videos->to(),
        //     from: $videos->from()
        // );

    }
}
