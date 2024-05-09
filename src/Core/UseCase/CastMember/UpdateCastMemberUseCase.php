<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\Update\CastMemberUpdateInputDTO;
use Core\DTO\CastMember\Update\CastMemberUpdateOutputDTO;

class UpdateCastMemberUseCase
{
    private $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberUpdateInputDTO $input): CastMemberUpdateOutputDTO
    {
        $castMember = $this->repository->findById($input->id);
        $castMember->update($input->name);

        $this->repository->update($castMember);

        return new CastMemberUpdateOutputDTO(
            $castMember->id(),
            $input->name,
            $castMember->type->value,
            $castMember->createdAt()
        );
    }
}
