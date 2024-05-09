<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\GenreInputDTO;
use Core\DTO\Genre\DeleteGenre\GenreDeleteOutputDTO;

class DeleteGenreUseCase
{
    protected $repository;

    public function __construct(private GenreRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(GenreInputDTO $input): GenreDeleteOutputDTO
    {
        $response = $this->repository->delete($input->id);

        return new GenreDeleteOutputDTO(
            success: $response
        );
    }
}