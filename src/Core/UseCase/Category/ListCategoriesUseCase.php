<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\DTO\Category\ListCategories\ListCategoriesOutputDTO;

class ListCategoriesUseCase
{
    protected $repository;

    public function __construct(private CategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(ListCategoriesInputDTO $input): ListCategoriesOutputDTO
    {
        $categories = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListCategoriesOutputDTO(
            items: $categories->items(),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            current_page: $categories->currentPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from()
        );

        /*
        return new ListCategoriesOutputDTO(
            items: array_map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'description' => $data->description,
                    'is_active' => (bool) $data->isActive,
                    'created_at' => (string) $data->createdAt()
                ];
            }, $categories->items()),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            current_page: $categories->currentPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from()
        );
        */
    }
}
