<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\GenreInputDTO;
use Core\DTO\Genre\GenreOutputDTO;

class ListGenreUseCase
{
    protected $repository;

    public function __construct(private GenreRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(GenreInputDTO $input): GenreOutputDTO
    {
        $genre = $this->repository->findById($input->id);

        return new GenreOutputDTO(
            id: $genre->id,
            name: $genre->name,
            is_active: $genre->isActive,
            created_at: $genre->createdAt(),
        );
    }
}