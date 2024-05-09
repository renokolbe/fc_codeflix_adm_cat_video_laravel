<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use App\Models\CastMember as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    private $model;

    public function __construct(Model $castMember)
    {
        $this->model = $castMember;
    }

    public function insert(EntityCastMember $castMember): EntityCastMember
    {
        $cast = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt(),
        ]);

        return $this->toCastMember($cast);
    }
    
    public function findById(string $id): EntityCastMember
    {
        $cast = $this->model->find($id);

        if (!$cast) {
            throw new NotFoundException("Cast Member {$id}  Not Found");
        }

        return $this->toCastMember($cast);
    }

    public function getIdsListIds(array $castMembersId = []): array
    {
        return $this->model
                    ->whereIn('id', $castMembersId)
                    ->pluck('id')
                    ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $castMembers = $this->model
                ->where(function ($query) use ($filter) {
                    if ($filter)
                        $query->where('name', 'LIKE', "%{$filter}%");
                })
                ->orderBy('name', $order)
                ->get();

        return $castMembers->toArray();
    }
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $paginator =$this->model
                    ->where(function ($query) use ($filter) {
                        if ($filter)
                            $query->where('name', 'LIKE', "%{$filter}%");
                        })
                    ->orderBy('name', $order)
                    ->paginate($totalPage);

        return new PaginationPresenter($paginator);

    }
    public function update(EntityCastMember $castMember): EntityCastMember
    {
        if (! $castMemberDb = $this->model->find($castMember->id())) {
            throw new NotFoundException("Cast Member {$castMember->id()}  Not Found");
        }

        $castMemberDb->update([
            'name' => $castMember->name,
        ]);

        $castMemberDb->refresh();

        return $this->toCastMember($castMemberDb);
    }
    public function delete(string $id): bool
    {        
        if (! $castMemberDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id}  Not Found");
        }

        return $castMemberDb->delete();

    }

    private function toCastMember(object $object): EntityCastMember
    {
        $entity = new EntityCastMember(
            id: new Uuid($object->id),
            name: $object->name,
            //type: $object->type == 2 ? CastMemberType::ACTOR : CastMemberType::DIRECTOR,
            type: CastMemberType::from($object->type),
            createdAt: $object->created_at
        );
        
        return $entity;
    }
}