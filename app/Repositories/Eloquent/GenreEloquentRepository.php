<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    private $model;

    public function __construct(Model $genre)
    {
        $this->model = $genre;
    }

    public function insert(EntityGenre $entityGenre): EntityGenre
    {
        $genre = $this->model->create([
            'id' => $entityGenre->id(),
            'name' => $entityGenre->name,
            'is_active' => $entityGenre->isActive,
            'created_at' => $entityGenre->createdAt(),
        ]);

        if (count($entityGenre->categoriesId) > 0) {
            $genre->categories()->sync($entityGenre->categoriesId);
        }

        return $this->toGenre($genre);
    }

    public function findById(string $id): EntityGenre
    {
        if (! $genre = $this->model->find($id)) {
            throw new NotFoundException('Genre Not Found');
        }

        return $this->toGenre($genre);
    }

    public function getIdsListIds(array $genresId = []): array
    {
        return $this->model
            ->whereIn('id', $genresId)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $genres = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $genres->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $paginator = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(EntityGenre $genre): EntityGenre
    {
        if (! $genreDb = $this->model->find($genre->id())) {
            throw new NotFoundException('Genre Not Found');
        }

        $genreDb->update([
            'name' => $genre->name,
            'is_active' => $genre->isActive,
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        $genreDb->refresh();

        return $this->toGenre($genreDb);
    }

    public function delete(string $id): bool
    {
        if (! $genreDb = $this->model->find($id)) {
            throw new NotFoundException('Genre Not Found');
        }

        return $genreDb->delete();
    }

    private function toGenre(object $object): EntityGenre
    {
        $entity = new EntityGenre(
            id: new Uuid($object->id),
            name: $object->name,
            createdAt: new DateTime($object->created_at),
        );

        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
