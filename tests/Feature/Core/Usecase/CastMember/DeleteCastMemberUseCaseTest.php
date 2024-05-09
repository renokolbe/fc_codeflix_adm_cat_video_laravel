<?php

namespace Tests\Feature\Core\Usecase\CastMember;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\DTO\CastMember\CastMemberInputDTO;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Tests\TestCase;

class DeleteCastMemberUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $castMemberDb = CastMemberModel::factory()->create();
        $usecase = new DeleteCastMemberUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );

        $response = $usecase->execute(new CastMemberInputDTO(
            id: $castMemberDb->id,
        ));

        $this->assertTrue($response->success);

        $this->assertSoftDeleted('cast_members', [
            'id' => $castMemberDb->id,
        ]);

    }
}
