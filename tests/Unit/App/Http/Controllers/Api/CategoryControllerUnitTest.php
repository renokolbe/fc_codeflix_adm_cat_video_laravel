<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use Core\DTO\Category\ListCategories\ListCategoriesOutputDTO;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryControllerUnitTest extends TestCase
{
    public function test_index()
    {
        $mockDTOOutput = Mockery::mock(ListCategoriesOutputDTO::class, [
            [], 1, 1, 1, 1, 1, 1, 1,
        ]);

        $mockRequest = Mockery::Mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('teste');

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->andReturn($mockDTOOutput);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);

        //dump($response);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);

        /**
         * Spies
         */
        $mockUseCaseSpy = Mockery::spy(ListCategoriesUseCase::class);
        $mockUseCaseSpy->shouldReceive('execute')->andReturn($mockDTOOutput);

        $response = $controller->index($mockRequest, $mockUseCaseSpy);

        $mockUseCaseSpy->shouldHaveReceived('execute');

        Mockery::close();
    }
}
