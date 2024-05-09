<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CastMemberEloquentRepository(new Model());
    }

    public function testImplementRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new EntityCastMember(
            name: 'Cast Member Name 1',
            type: CastMemberType::ACTOR
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityCastMember::class, $response);
        $this->assertDatabaseHas('cast_members', [
            'id' => $response->id,
            'name' => 'Cast Member Name 1',
            'type' => CastMemberType::ACTOR->value,
        ]);
    }

    public function testFindByIDNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fake-id');
    }

    public function testFindByID()
    {
        $castMember = Model::factory()->create();
        $response = $this->repository->findById($castMember->id);

        $this->assertInstanceOf(EntityCastMember::class, $response);
        $this->assertEquals($castMember->id, $response->id);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();

        $this->assertCount(0, $response);
    }

    public function testFindAll()
    {
        $castMembers = Model::factory()->count(10)->create();
        $response = $this->repository->findAll();

        $this->assertCount(count($castMembers), $response);
    }

    public function testPaginate()
    {
        $castMembers = Model::factory()->count(100)->create();
        $response = $this->repository->paginate();
        //dump($response);

        $this->assertCount(15, $response->items());
        $this->assertEquals(count($castMembers), $response->total());

    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();
        //dump($response);
        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }

    public function testTotalPage()
    {
        $castMembers = Model::factory()->count(100)->create();
        $response = $this->repository->paginate(
            totalPage: 10
        );
        //dump($response);

        $this->assertCount(10, $response->items());
        $this->assertEquals(count($castMembers), $response->total());

    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->update(new EntityCastMember(
            name: 'Cast Member Name Updated',
            type: CastMemberType::ACTOR
        ));
    }

    public function testUpdate()
    {
        $castMemberDB = Model::factory()->create();

        $castMember = new EntityCastMember(
            id: new Uuid($castMemberDB->id),
            name: 'Cast Member Name Updated',
            type: $castMemberDB->type == 2 ? CastMemberType::ACTOR : CastMemberType::DIRECTOR
        );

        $response = $this->repository->update($castMember);

        $this->assertInstanceOf(EntityCastMember::class, $response);
        $this->assertDatabaseHas('cast_members', [
            'id' => $castMemberDB->id,
            'name' => 'Cast Member Name Updated',
            'type' => $castMemberDB->type,
        ]);
        $this->assertNotEquals($castMemberDB->name, $response->name);
        $this->assertEquals('Cast Member Name Updated', $response->name);
    }

    public function testDeleleNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake-id');
    }

    public function testDelete()
    {
        $castMemberDB = Model::factory()->create();
        $response = $this->repository->delete($castMemberDB->id);
        $this->assertTrue($response);
        $this->assertSoftDeleted('cast_members', [
            'id' => $castMemberDB->id,
        ]);

    }
}
