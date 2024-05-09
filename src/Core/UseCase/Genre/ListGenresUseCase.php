<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\List\ListGenresInputDTO;
use Core\DTO\Genre\List\ListGenresOutputDTO;

class ListGenresUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(ListGenresInputDTO $input): ListGenresOutputDTO
    {
        $genres = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListGenresOutputDTO(
            items: $genres->items(),
            total: $genres->total(),
            last_page: $genres->lastPage(),
            first_page: $genres->firstPage(),
            current_page: $genres->currentPage(),
            per_page: $genres->perPage(),
            to: $genres->to(),
            from: $genres->from()
        );
    }
}
