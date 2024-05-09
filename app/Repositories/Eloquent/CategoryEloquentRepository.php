<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Entity\Entity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    private $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Entity $entity): Entity
    {
        $category = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): Entity
    {
        if (! $category = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $this->toCategory($category);
    }

    public function getIdsListIds(array $categoriesId = []): array
    {
        return $this->model
            ->whereIn('id', $categoriesId)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        // $query = $this->model;

        // if ($filter){
        //     $query->where('name', 'LIKE', "%{$filter}%");
        // }

        // $query->orderBy('id', $order);
        // $paginator =$query->paginate();

        $paginator = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('id', $order)
            ->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(Entity $category): Entity
    {
        if (! $categoryDb = $this->model->find($category->id())) {
            throw new NotFoundException('Category Not Found');
        }

        $categoryDb->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
        ]);

        $categoryDb->refresh();

        return $this->toCategory($categoryDb);
    }

    public function delete(string $id): bool
    {
        if (! $categoryDb = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $categoryDb->delete();
    }

    private function toCategory(object $object): EntityCategory
    {
        $entity = new EntityCategory(
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );

        ((bool) $object->is_active) ? $entity->activate() : $entity->disable();

        return $entity;
    }
}
