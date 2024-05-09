<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\Delete\CastMemberDeleteOutputDTO;

class DeleteCastMemberUseCase
{
    private $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDTO $input): CastMemberDeleteOutputDTO
    {
        $response = $this->repository->delete($input->id);

        return new CastMemberDeleteOutputDTO(
            success: $response
        );
    }
}
