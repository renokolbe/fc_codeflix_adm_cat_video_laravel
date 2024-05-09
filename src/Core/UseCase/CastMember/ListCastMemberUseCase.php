<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\CastMemberOutputDTO;

class ListCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(CastMemberInputDTO $input): CastMemberOutputDTO
    {
        $castMember = $this->repository->findById($input->id);

        return new CastMemberOutputDTO(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            created_at: $castMember->createdAt()
        );
    }
}
