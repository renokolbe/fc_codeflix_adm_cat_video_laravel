<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;

class UpdateCategoryUseCase
{
    protected $repository;

    public function __construct(private CategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(CategoryUpdateInputDTO $input): CategoryUpdateOutputDTO
    {
        $category = $this->repository->findById($input->id);

        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description,
        );

        $categoryUpdated = $this->repository->update($category);

        return new CategoryUpdateOutputDTO(
            id: $categoryUpdated->id(),
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            is_active: $categoryUpdated->isActive,
            created_at: $categoryUpdated->createdAt(),
        );
    }
}