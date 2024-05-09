<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\CastMember as EntityCastMember;

interface CastMemberRepositoryInterface
{
    public function insert(EntityCastMember $castMember): EntityCastMember;
    public function findById(string $id): EntityCastMember;
    public function getIdsListIds(array $castMembersId = []): array;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface;
    public function update(EntityCastMember $castMember): EntityCastMember;
    public function delete(string $id): bool;
}