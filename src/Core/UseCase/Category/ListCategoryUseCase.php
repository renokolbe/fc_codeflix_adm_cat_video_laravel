<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\CategoryOutputDTO;

class ListCategoryUseCase
{
    protected $repository;

    public function __construct(private CategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(CategoryInputDTO $input): CategoryOutputDTO
    {
        $category = $this->repository->findById($input->id);

        return new CategoryOutputDTO(
            id: $category->id,
            name: $category->name,
            description: $category->description,
            is_active: $category->isActive,
            created_at: $category->createdAt(),
        );
    }
}