<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\{
    DeleteInputVideoDTO,
    DeleteOutputVideoDTO
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeleteVideoUseCaseUnitTest extends TestCase
{

    public function testNotFound()
    {
        $useCase = new DeleteVideoUseCase($this->mockRepository('uuid_fake', false));
        $responseUseCase = $useCase->execute($this->mockInputDTO('uuid_fake'));

        //$this->assertTrue(true);
        $this->assertInstanceOf(DeleteOutputVideoDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);

        $this->tearDown();

    }
    public function testDelete()
    {
        $uuid = Uuid::random();

        $useCase = new DeleteVideoUseCase($this->mockRepository($uuid));
        $responseUseCase = $useCase->execute($this->mockInputDTO($uuid));

        $this->assertInstanceOf(DeleteOutputVideoDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

        $this->tearDown();
    }

    private function mockInputDTO(string $id)
    {

        return Mockery::mock(DeleteInputVideoDTO::class, [$id] );
    }

    private function mockRepository(string $id, bool $deleted = true)
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('delete')
                        ->with($id)
                        ->times(1)
                        ->andReturn($deleted);
        
        return $mockRepository;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}