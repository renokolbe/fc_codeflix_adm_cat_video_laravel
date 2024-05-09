<?php

namespace Tests\Feature\Core\Usecase\CastMember;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\DTO\CastMember\List\ListCastMembersOutputDTO;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Tests\TestCase;

class ListCastMembersUseCaseTest extends TestCase
{
    public function testFind()
    {
        $castMemberDb = CastMemberModel::factory()->count(100)->create();
        $usecase = new ListCastMembersUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );
        $response = $usecase->execute(new ListCastMembersInputDTO(
        ));

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $response);
        $this->assertCount(15, $response->items);
        $this->assertEquals(count($castMemberDb), $response->total);

    }

    public function testFindEmpty()
    {
        $usecase = new ListCastMembersUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );
        $response = $usecase->execute(new ListCastMembersInputDTO(
        ));

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $response);
        $this->assertCount(0, $response->items);

    }
}
