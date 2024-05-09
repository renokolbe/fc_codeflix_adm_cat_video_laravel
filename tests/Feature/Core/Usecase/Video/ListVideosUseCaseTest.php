<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\Video;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\{
    ListVideosUseCase,
    DTO\PaginateInputVideoDTO,
//    DTO\PaginateOutputVideoDTO
};
use Tests\TestCase;

class ListVideosUseCaseTest extends TestCase
{
    public function testPaginate()
    {
        $videosDb = Video::factory()->count(100)->create();

        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)            
        );

        $response = $useCase->execute(new PaginateInputVideoDTO());

        //$this->assertInstanceOf(PaginateOutputVideoDTO::class, $response);
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertEquals(100, $response->total());
    }

    /**
     * @dataProvider dataProviderPagination
     */
    public function testPaginateWithPagination(
        int $page = 1,
        int $totalPerPage = 15,
        int $total = 100)
    {
        $videosDb = Video::factory()->count($total)->create();
        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)            
        );

        $input = new PaginateInputVideoDTO(
            filter: '',
            order: 'DESC',
            page: $page,
            totalPage: $totalPerPage
        );

        $response = $useCase->execute($input);

        //$this->assertInstanceOf(PaginateOutputVideoDTO::class, $response);
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount($totalPerPage, $response->items());
        $this->assertEquals(100, $response->total());
        $this->assertEquals($page, $response->currentPage());

    }

    protected function dataProviderPagination(): array
    {
        return [
            'Default' => [
            ],
            'Page 2' => [
                'page' => 2,
                'totalPerPage' => 10,
                'total' => 100
            ],
        ];
    }

    public function testPaginateEmpty()
    {
        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)            
        );

        $response = $useCase->execute(new PaginateInputVideoDTO());

        //$this->assertInstanceOf(PaginateOutputVideoDTO::class, $response);
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertEquals(0, $response->total());
    }
}
