<?php

namespace Tests\Feature\Core\Usecase\CastMember;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\DTO\CastMember\Create\CastMemberCreateInputDTO;
use Core\DTO\CastMember\Create\CastMemberCreateOutputDTO;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCastMemberUseCaseTest extends TestCase
{
    public function testInsert()
    {
        $usecase = new CreateCastMemberUseCase(
            new CastMemberEloquentRepository(new CastMemberModel())
        );

        $respose = $usecase->execute(
            new CastMemberCreateInputDTO(
                name: 'Actor Name 1',
                type: 2
            )
        );

        $this->assertInstanceOf(CastMemberCreateOutputDTO::class, $respose);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'Actor Name 1',
            'type' => 2
        ]);

    }
}
