<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\Create\{
    CastMemberCreateInputDTO,
    CastMemberCreateOutputDTO
};

class CreateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(CastMemberCreateInputDTO $input): CastMemberCreateOutputDTO
    {
        $castMember = new CastMember(
            name: $input->name,
            type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR
        );

        $this->repository->insert($castMember);

        return new CastMemberCreateOutputDTO(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            created_at: $castMember->createdAt()
        );
    }
}