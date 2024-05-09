<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Image as ValueObjectImage;
use Core\Domain\ValueObject\Media as ValueObjectMedia;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new VideoEloquentRepository(new Model());
    }

    public function testImplementRepository()
    {
        $this->assertInstanceOf(VideoEloquentRepository::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(Video::class, $response);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id,
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'opened' => $entity->opened,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
        ]);
    }

    public function testInsertWithrelationships()
    {
        $categories = Category::factory()->count(2)->create();
        $genres = Genre::factory()->count(1)->create();
        $castMembers = CastMember::factory()->count(5)->create();

        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120
        );

        foreach ($categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($genres as $genre) {
            $entity->addGenreId($genre->id);
        }

        foreach ($castMembers as $castMember) {
            $entity->addCastMemberId($castMember->id);
        }

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $response->id(),
        ]);

        $this->assertCount(count($categories), $response->categoriesId);

        $this->assertDatabaseCount('category_video', count($categories));

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_video', [
                'video_id' => $response->id(),
                'category_id' => $category->id,
            ]);
        }

        $this->assertEquals($categories->pluck('id')->toArray(), $response->categoriesId);

        $this->assertCount(count($genres), $response->genresId);

        $this->assertDatabaseCount('genre_video', count($genres));

        foreach ($genres as $genre) {
            $this->assertDatabaseHas('genre_video', [
                'video_id' => $response->id(),
                'genre_id' => $genre->id,
            ]);
        }

        $this->assertEquals($genres->pluck('id')->toArray(), $response->genresId);

        $this->assertCount(count($castMembers), $response->castMembersIds);

        $this->assertDatabaseCount('cast_member_video', count($castMembers));

        foreach ($castMembers as $castMember) {
            $this->assertDatabaseHas('cast_member_video', [
                'video_id' => $response->id(),
                'cast_member_id' => $castMember->id,
            ]);
        }

        $this->assertEquals($castMembers->pluck('id')->toArray(), $response->castMembersIds);

    }

    public function testFindById()
    {
        $dbVideo = Model::factory()->create();
        $response = $this->repository->findById($dbVideo->id);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertEquals($dbVideo->id, $response->id);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fake-id');
    }

    public function testFindAll()
    {
        $dbVideos = Model::factory()->count(10)->create();
        $response = $this->repository->findAll();
        $this->assertCount(count($dbVideos), $response);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create();
        Model::factory()->count(5)->create([
            'title' => 'Teste',
        ]);

        $response = $this->repository->findAll(
            filter: 'Teste',
        );

        $this->assertCount(5, $response);

        $response = $this->repository->findAll(
            filter: 'XXXXX',
        );

        $this->assertCount(0, $response);

        $response = $this->repository->findAll();

        $this->assertCount(15, $response);
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testPaginate()
    {
        $dbVideos = Model::factory()->count(20)->create();
        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(2, $response->lastPage());
        $this->assertEquals(20, $response->total());
    }

    /**
     * @dataProvider dataProviderPagination
     */
    public function testPaginationWithDataProvider(
        int $page,
        int $totalPage,
        int $total = 50,
    ) {
        Model::factory()->count($total)->create();

        $response = $this->repository->paginate(
            page: $page,
            totalPage: $totalPage
        );

        $this->assertCount($totalPage, $response->items());
        $this->assertEquals($total, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($totalPage, $response->perPage());
    }

    public function dataProviderPagination(): array
    {
        return [
            [
                'page' => 1,
                'totalPage' => 10,
                'total' => 100,
            ], [
                'page' => 2,
                'totalPage' => 15,
            ], [
                'page' => 3,
                'totalPage' => 15,
            ], [
                'page' => 4,
                'totalPage' => 5,
            ],
        ];
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);
        $video = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120
        );

        $this->repository->update($video);
    }

    public function testUpdate()
    {
        $dbVideo = Model::factory()->create();
        $video = new Video(
            id: new Uuid($dbVideo->id),
            title: 'Video Title 1 Updated',
            description: 'Video Description 1 Updated',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120
        );

        $response = $this->repository->update($video);

        $this->assertInstanceOf(Video::class, $response);

        $this->assertDatabaseHas('videos', [
            'id' => $response->id(),
            'title' => 'Video Title 1 Updated',
            'description' => 'Video Description 1 Updated',
        ]);
    }

    public function testUpdateWithRelationships()
    {
        $dbVideo = Model::factory()->create();
        $video = new Video(
            id: new Uuid($dbVideo->id),
            title: 'Video Title 1 Updated',
            description: 'Video Description 1 Updated',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120
        );

        $categories = Category::factory()->count(4)->create();
        $genres = Genre::factory()->count(2)->create();
        $castMembers = CastMember::factory()->count(10)->create();

        foreach ($categories as $category) {
            $video->addCategoryId($category->id);
        }

        foreach ($genres as $genre) {
            $video->addGenreId($genre->id);
        }

        foreach ($castMembers as $castMember) {
            $video->addCastMemberId($castMember->id);
        }

        $response = $this->repository->update($video);

        $this->assertInstanceOf(Video::class, $response);

        $this->assertDatabaseHas('videos', [
            'id' => $response->id(),
            'title' => 'Video Title 1 Updated',
            'description' => 'Video Description 1 Updated',
        ]);

        $this->assertCount(count($categories), $response->categoriesId);

        $this->assertDatabaseCount('category_video', count($categories));

        $this->assertEquals($categories->pluck('id')->toArray(), $response->categoriesId);

        $this->assertCount(count($genres), $response->genresId);

        $this->assertDatabaseCount('genre_video', count($genres));

        $this->assertEquals($genres->pluck('id')->toArray(), $response->genresId);

        $this->assertCount(count($castMembers), $response->castMembersIds);

        $this->assertDatabaseCount('cast_member_video', count($castMembers));

        $this->assertEquals($castMembers->pluck('id')->toArray(), $response->castMembersIds);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake-id');
    }

    public function testDelete()
    {
        $dbVideo = Model::factory()->create();
        $this->repository->delete($dbVideo->id);
        $this->assertSoftDeleted('videos', [
            'id' => $dbVideo->id,
        ]);
    }

    public function testInsertWithMediaTrailer()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            trailerFile: new ValueObjectMedia(
                filePath: 'trailer.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'type' => MediaTypes::TRAILER->value,
            'media_status' => MediaStatus::PROCESSING->value,
            'file_path' => 'trailer.mp4',
            'encoded_path' => '',
        ]);

        $entity->setTrailerFile(new ValueObjectMedia(
            filePath: 'trailer.mp4',
            mediaStatus: MediaStatus::COMPLETE,
            encodedPath: 'encoded/trailer.mp4',
        ));

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'type' => MediaTypes::TRAILER->value,
            'media_status' => MediaStatus::COMPLETE->value,
            'file_path' => 'trailer.mp4',
            'encoded_path' => 'encoded/trailer.mp4',
        ]);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertNotNull($response->trailerFile());

    }

    public function testInsertWithMediaVideo()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            videoFile: new ValueObjectMedia(
                filePath: 'video.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'type' => MediaTypes::VIDEO->value,
            'media_status' => MediaStatus::PROCESSING->value,
            'file_path' => 'video.mp4',
            'encoded_path' => '',
        ]);

        $entity->setVideoFile(new ValueObjectMedia(
            filePath: 'video.mp4',
            mediaStatus: MediaStatus::COMPLETE,
            encodedPath: 'encoded/video.mp4',
        ));

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'type' => MediaTypes::VIDEO->value,
            'media_status' => MediaStatus::COMPLETE->value,
            'file_path' => 'video.mp4',
            'encoded_path' => 'encoded/video.mp4',
        ]);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertNotNull($response->videoFile());

    }

    public function testInsertWithImageBanner()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            bannerFile: new ValueObjectImage(
                path: 'banner.png',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::BANNER->value,
            'path' => 'banner.png',
        ]);

        $entity->setBannerFile(new ValueObjectImage(
            path: 'new_banner.png',
        ));

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::BANNER->value,
            'path' => 'new_banner.png',
        ]);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertNotNull($response->bannerFile());

    }

    public function testInsertWithImageThumb()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            thumbFile: new ValueObjectImage(
                path: 'thumb.png',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::THUMB->value,
            'path' => 'thumb.png',
        ]);

        $entity->setThumbFile(new ValueObjectImage(
            path: 'new_thumb.png',
        ));

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::THUMB->value,
            'path' => 'new_thumb.png',
        ]);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertNotNull($response->thumbFile());

    }

    public function testInsertWithImageThumbHalf()
    {
        $entity = new Video(
            title: 'Video Title 1',
            description: 'Video Description 1',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            thumbHalf: new ValueObjectImage(
                path: 'thumbhalf.png',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::THUMB_HALF->value,
            'path' => 'thumbhalf.png',
        ]);

        $entity->setThumbHalfFile(new ValueObjectImage(
            path: 'new_thumbhalf.png',
        ));

        $response = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'type' => ImageTypes::THUMB_HALF->value,
            'path' => 'new_thumbhalf.png',
        ]);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertNotNull($response->thumbHalf());

    }
}
