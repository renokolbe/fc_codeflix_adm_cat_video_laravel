<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;

class DeleteCategoryUseCase
{
    protected $repository;

    public function __construct(private CategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(CategoryInputDTO $input): CategoryDeleteOutputDTO
    {
        $response = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDTO(
            success: $response
        );
    }
}
