<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface, 
    CategoryRepositoryInterface, 
    GenreRepositoryInterface, 
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface, 
    TransactionInterface
};
use Core\UseCase\Video\BaseVideoUseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class BaseVideoUseCaseUnitTest extends TestCase
{

    protected $useCase;

    abstract protected function nameActionRepository(): string;
    abstract protected function getUseCase(): string;

    abstract protected function createMockInputDTO(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    );

    protected function createUseCase(
        int $timesCallActionRepository = 1,
        int $timesCallUpdateMediaRepository = 1,
        int $timesCallCommitMethodTransaction = 1,
        int $timesCallRollbackMethodTransaction = 0,
        int $timesCallStoreFileStorage = 0,
        int $timesCallDispatchMethod = 0
    )
    {
        $this->useCase = new ($this->getUseCase())(
            repository: $this->createMockRepository(
                timesCallAction: $timesCallActionRepository,
                timesCallUpdateMedia: $timesCallUpdateMediaRepository
            ),
            transaction: $this->createMockTransaction(
                timesCallCommit: $timesCallCommitMethodTransaction,
                timesCallRollback: $timesCallRollbackMethodTransaction
            ),
            storage: $this->createMockFileStorage(
                timesCall: $timesCallStoreFileStorage
            ),
            eventManager: $this->createMockEventManager(
                timesCall: $timesCallDispatchMethod
            ),
            categoryRepository: $this->createMockRepositoryCategory(),
            genreRepository: $this->createMockRepositoryGenre(),
            castMemberRepository: $this->createMockRepositoryCastMember(),
        );

    }


    /**
     * @dataProvider dataProviderIds
     */
    public function testValidateCategoriesIds(
        string $label,
        array $ids
    )
    {        
        $this->createUseCase(
            timesCallActionRepository: 0,
            timesCallUpdateMediaRepository: 0,
            timesCallCommitMethodTransaction: 0,
        );

        $this->expectException(NotFoundException::class);
        // Versao em desuso a partir do PHPUnit 9.6
        // $this->expectErrorMessage(sprintf(
        //     '%s %s not found',
        //     $label,
        //     implode(', ', $ids)
        // ));
        $this->expectExceptionMessage(sprintf(
            '%s %s not found',
            $label,
            implode(', ', $ids)
        ));

        $this->useCase->exec(
            input: $this->createMockInputDTO(
                categoriesIds: $ids,
            ),
        );

    }

    public function dataProviderIds()
    {
        return [
            ['Category', ['fake_id_1']],
            ['Categories', ['fake_id_1', 'fake_id_2']],
            ['Categories', ['fake_id_1', 'fake_id_2', 'fake_id_3']],
        ];
    }

    public function testUploadFiles()
    {
        $this->createUseCase(
            timesCallStoreFileStorage: 5,
            timesCallDispatchMethod: 1,
        );

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(
                videoFile: ['tmp' => 'path/to/video/file.mp4',],
                trailerFile: ['tmp' => 'path/to/trailer/trailer.mp4',],
                thumbFile: ['tmp' => 'path/to/thumbnail/image.png',],
                thumbHalf: ['tmp' => 'path/to/thumbnail/half/image.png',],
                bannerFile: ['tmp' => 'path/to/banner/image.png',],
            ),
        );

        //dump($response);

        $this->assertNotNull($response->videoFile);
        $this->assertNotNull($response->trailerFile);
        $this->assertNotNull($response->thumbFile);
        $this->assertNotNull($response->thumbHalf);
        $this->assertNotNull($response->bannerFile);

    }

    /**
     * @dataProvider dataProviderFiles
     */
    public function testUploadFilesWithDataProvider(
        array $video,
        array $trailer,
        array $thumb,
        array $thumbHalf,
        array $banner,
        int $timesCallStoreFileStorage,
        int $timesCallDispatchMethod = 0
    )
    {
        $this->createUseCase(
            timesCallStoreFileStorage: $timesCallStoreFileStorage,
            timesCallDispatchMethod: $timesCallDispatchMethod
        );

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(
                videoFile: $video['value'],
                trailerFile: $trailer['value'],
                thumbFile: $thumb['value'],
                thumbHalf: $thumbHalf['value'],
                bannerFile: $banner['value'],
            ),
        );

        //dump($response);

        $this->assertEquals($response->videoFile, $video['expected']);
        $this->assertEquals($response->trailerFile, $trailer['expected']);
        $this->assertEquals($response->thumbFile, $thumb['expected']);
        $this->assertEquals($response->thumbHalf, $thumbHalf['expected']);
        $this->assertEquals($response->bannerFile, $banner['expected']);

    }

    public function dataProviderFiles(): array
    {
        return [
            [
                'video' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'trailer' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'thumb' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'thumbHalf' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'banner' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'timesStoreFileStorage' => 5,
                'timesDispatchMethod' => 1,
            ],
            [
                'video' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'timesStoreFileStorage' => 3,
                'timesDispatchMethod' => 1,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'path/to/video/file.mp4'], 'expected' => 'path/to/video/file.mp4'],
                'timesStoreFileStorage' => 2,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'timesStoreFileStorage' => 0,
            ],
        ];
    }

    private function createMockRepository(
        int $timesCallAction,
        int $timesCallUpdateMedia
    )
    {
        //$entity = $this->createMockEntity();
        $entity = $this->createEntity();
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive($this->nameActionRepository())
                        ->times($timesCallAction)
                        ->andReturn($entity);

        $mockRepository->shouldReceive('findById')
                        ->andReturn($entity);

        $mockRepository->shouldReceive('updateMedia')
                        ->times($timesCallUpdateMedia);
        
        return $mockRepository;
    }

    private function createMockRepositoryCategory(array $categoriesResponse = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')
                        ->andReturn($categoriesResponse);
        
        return $mockRepository;
    }

    private function createMockRepositoryGenre(array $genresResponse = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')
                        ->andReturn($genresResponse);
        
        return $mockRepository;
    }

    private function createMockRepositoryCastMember(array $castMembersResponse = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')
                        ->andReturn($castMembersResponse);
        
        return $mockRepository;
    }

    private function createMockTransaction
    (
        int $timesCallCommit,
        int $timesCallRollback
    )
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit')
                        ->times($timesCallCommit)
                        ;
        $mockTransaction->shouldReceive('rollback')
                        ->times($timesCallRollback)
                        ;

        return $mockTransaction;
    }

    private function createMockFileStorage(int $timesCall)
    {
        $mockFileStorage = Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mockFileStorage->shouldReceive('store')
                        ->times($timesCall)
                        ->andReturn('path/to/video/file.mp4')
                        ;

        return $mockFileStorage;
    }

    private function createMockEventManager(
        int $timesCall
    )
    {
        $mockEventManager = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mockEventManager->shouldReceive('dispatch')->times($timesCall);

        return $mockEventManager;
    }

    // private function createMockEntity()
    // {
    //     return Mockery::mock(EntityVideo::class, [
    //         'title' => 'Video title',
    //         'description' => 'Video description',
    //         'yearLaunched' => 2020,
    //         'duration' => 120,
    //         'opened' => true,
    //         'rating' => Rating::L,
    //     ]);
    // }

    private function createEntity()
    {

        return new EntityVideo(
            title: 'Video title',
            description: 'Video description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::L
        );

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
