<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\DTO\CastMember\List\ListCastMembersOutputDTO;

class ListCastMembersUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(ListCastMembersInputDTO $input): ListCastMembersOutputDTO
    {
        $castMembers = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListCastMembersOutputDTO(
            items: $castMembers->items(),
            total: $castMembers->total(),
            last_page: $castMembers->lastPage(),
            first_page: $castMembers->firstPage(),
            current_page: $castMembers->currentPage(),
            per_page: $castMembers->perPage(),
            to: $castMembers->to(),
            from: $castMembers->from()
        );
    }
}