<?php

namespace Tests\Feature\Core\Usecase\CastMember;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\DTO\CastMember\CastMemberOutputDTO;
use Core\UseCase\CastMember\ListCastMemberUseCase;

use Tests\TestCase;

class ListCastMemberUseCaseTest extends TestCase
{
    public function testFind()
    {
        $castMemberDb = CastMemberModel::factory()->create();
        $usecase = new ListCastMemberUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );
        $response = $usecase->execute(new CastMemberInputDTO(
            id: $castMemberDb->id
        ));

        $this->assertInstanceOf(CastMemberOutputDTO::class, $response);
        $this->assertEquals($castMemberDb->id, $response->id);
        $this->assertEquals($castMemberDb->name, $response->name);
        $this->assertEquals($castMemberDb->type->value, $response->type);

    }
}
