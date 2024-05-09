<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDTO;
use Core\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDeleteCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->times(1)->andReturn(true);

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [
            $uuid,
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

        /**
         * Spies
         */
        /*
        // Nao há necessidade do SPIES se usar no mock o parametro times() para verificar a quatidade de chamadas da funcionalidade

                 $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
                 $this->spy->shouldReceive('delete')->andReturn(true);

                 $useCaseSpy = new DeleteCategoryUseCase($this->spy);
                 $useCaseSpy->execute($this->mockInputDto);

                 $this->spy->shouldHaveReceived('delete');
        */
        $this->tearDown();

    }

    public function testDeleteFalse()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(false);

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [
            $uuid,
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
