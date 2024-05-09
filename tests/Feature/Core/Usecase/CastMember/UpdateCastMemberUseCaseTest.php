<?php

namespace Tests\Feature\Core\Usecase\CastMember;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\DTO\CastMember\Update\CastMemberUpdateInputDTO;
use Core\DTO\CastMember\Update\CastMemberUpdateOutputDTO;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Tests\TestCase;

class UpdateCastMemberUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $castMemberDb = CastMemberModel::factory()->create();
        $usecase = new UpdateCastMemberUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );

        $newName = 'Cast Member Name Updated';

        $response = $usecase->execute(new CastMemberUpdateInputDTO(
            id: $castMemberDb->id,
            name: $newName,
        ));

        $this->assertInstanceOf(CastMemberUpdateOutputDTO::class, $response);
        $this->assertEquals($castMemberDb->id, $response->id);
        $this->assertEquals($newName, $response->name);
        $this->assertEquals($castMemberDb->type->value, $response->type);

    }
}
