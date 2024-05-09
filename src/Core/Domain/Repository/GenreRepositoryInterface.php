<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Genre as EntityGenre;

interface GenreRepositoryInterface
{
    public function insert(EntityGenre $genre): EntityGenre;
    public function findById(string $id): EntityGenre;
    public function getIdsListIds(array $genresId = []): array;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface;
    public function update(EntityGenre $genre): EntityGenre;
    public function delete(string $id): bool;
}