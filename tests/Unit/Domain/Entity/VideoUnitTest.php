<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new DateTime(date('Y-m-d H:i:s'));

        $video = new Video(
            id: new Uuid($uuid),
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            createdAt: $createdAt,
        );

        $this->assertEquals($uuid, $video->id);
        $this->assertEquals('New Title', $video->title);
        $this->assertEquals('New Title Description', $video->description);
        $this->assertEquals(2024, $video->yearLaunched);
        $this->assertEquals(98, $video->duration);
        $this->assertEquals(true, $video->opened);
        $this->assertEquals(false, $video->published);
        $this->assertEquals(Rating::ER, $video->rating);
        $this->assertEquals($createdAt, $video->createdAt);
    }

    public function testNewId()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertNotEmpty($video->id());
        $this->assertNotEmpty($video->createdAt());
        $this->assertEquals(true, $video->published);
    }

    public function testAddCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->categoriesId);

        $video->addCategoryId(
            categoryId: $categoryId
        );

        $this->assertCount(1, $video->categoriesId);

    }

    public function testRemoveCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->categoriesId);

        $video->addCategoryId(
            categoryId: $categoryId
        );

        $categoryId = (string) RamseyUuid::uuid4();
        $video->addCategoryId(
            categoryId: $categoryId
        );

        $this->assertCount(2, $video->categoriesId);

        $video->removeCategoryId(
            categoryId: $categoryId
        );

        $this->assertCount(1, $video->categoriesId);

    }

    public function testAddGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->genresId);

        $video->addGenreId(
            genreId: $genreId
        );

        $this->assertCount(1, $video->genresId);

    }

    public function testRemoveGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->genresId);

        $video->addGenreId(
            genreId: $genreId
        );

        $genreId = (string) RamseyUuid::uuid4();
        $video->addGenreId(
            genreId: $genreId
        );

        $this->assertCount(2, $video->genresId);

        $video->removeGenreId(
            genreId: $genreId
        );

        $this->assertCount(1, $video->genresId);

    }

    public function testAddCastMemberId()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->castMembersIds);

        $video->addCastMemberId(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $video->castMembersIds);

    }

    public function testRemoveCastMemberId()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        $this->assertCount(0, $video->castMembersIds);

        $video->addCastMemberId(
            castMemberId: $castMemberId
        );

        $castMemberId = (string) RamseyUuid::uuid4();
        $video->addCastMemberId(
            castMemberId: $castMemberId
        );

        $this->assertCount(2, $video->castMembersIds);

        $video->removeCastMemberId(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $video->castMembersIds);

    }

    public function testValueObjectImageThumbFile()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            thumbFile: new Image(path: 'caminho/para/imagem.png'),
        );

        //dump($video->thumbFile()->path());

        $this->assertNotNull($video->thumbFile());
        $this->assertInstanceOf(Image::class, $video->thumbFile());
        $this->assertEquals('caminho/para/imagem.png', $video->thumbFile()->path());
    }

    public function testValueObjectImageThumbFileNull()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        //dump($video->thumbFile()->path());

        $this->assertNull($video->thumbFile());
    }

    public function testValueObjectImageThumbHalf()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            thumbHalf: new Image(path: 'caminho/para/imagem.png'),
        );

        //dump($video->thumbFile()->path());

        $this->assertNotNull($video->thumbHalf());
        $this->assertInstanceOf(Image::class, $video->thumbHalf());
        $this->assertEquals('caminho/para/imagem.png', $video->thumbHalf()->path());
    }

    public function testValueObjectImageThumbHalfNull()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        //dump($video->thumbFile()->path());

        $this->assertNull($video->thumbHalf());
    }

    public function testValueObjectImageBanner()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            bannerFile: new Image(path: 'caminho/para/banner.png'),
        );

        //dump($video->thumbFile()->path());

        $this->assertNotNull($video->bannerFile());
        $this->assertInstanceOf(Image::class, $video->bannerFile());
        $this->assertEquals('caminho/para/banner.png', $video->bannerFile()->path());
    }

    public function testValueObjectImageBannerFileNull()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        //dump($video->thumbFile()->path());

        $this->assertNull($video->bannerFile());
    }

    public function testValueObjectMediaTrailerNull()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        //dump($video->trailerFile());

        $this->assertNull($video->trailerFile());
    }

    public function testValueObjectMediaTrailer()
    {

        $trailerFile = new Media(
            filePath: 'caminho/para/trailer.raw',
            mediaStatus: MediaStatus::PENDING,
            encodedPath: 'caminho/encoded/trailer.mp4',
        );

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            trailerFile: $trailerFile,
        );

        //dump($video->trailerFile());

        $this->assertNotNull($video->trailerFile());
        $this->assertInstanceOf(Media::class, $video->trailerFile());
        $this->assertEquals('caminho/para/trailer.raw', $video->trailerFile()->filePath);
        $this->assertEquals(MediaStatus::PENDING, $video->trailerFile()->mediaStatus);
        $this->assertEquals('caminho/encoded/trailer.mp4', $video->trailerFile()->encodedPath);
    }

    public function testValueObjectMediaVideoNull()
    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

        //dump($video->trailerFile());

        $this->assertNull($video->videoFile());
    }

    public function testValueObjectMediaVideo()
    {

        $videoFile = new Media(
            filePath: 'caminho/para/videofile.raw',
            mediaStatus: MediaStatus::COMPLETE,
            encodedPath: 'caminho/encoded/videofile.mp4',
        );

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            videoFile: $videoFile,
        );

        //dump($video->trailerFile());

        $this->assertNotNull($video->videoFile());
        $this->assertInstanceOf(Media::class, $video->videoFile());
        $this->assertEquals('caminho/para/videofile.raw', $video->videoFile()->filePath);
        $this->assertEquals(MediaStatus::COMPLETE, $video->videoFile()->mediaStatus);
        $this->assertEquals('caminho/encoded/videofile.mp4', $video->videoFile()->encodedPath);
    }

    public function testValueObjectMediaVideoWithoutEncodedPath()
    {

        $videoFile = new Media(
            filePath: 'caminho/para/videofile.raw',
            mediaStatus: MediaStatus::PROCESSING,
        );

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            videoFile: $videoFile,
        );

        //dump($video->trailerFile());

        $this->assertNotNull($video->videoFile());
        $this->assertInstanceOf(Media::class, $video->videoFile());
        $this->assertEquals('caminho/para/videofile.raw', $video->videoFile()->filePath);
        $this->assertEquals(MediaStatus::PROCESSING, $video->videoFile()->mediaStatus);
        $this->assertEquals('', $video->videoFile()->encodedPath);
    }

    public function testException()
    {
        $this->expectException(NotificationException::class);

        $video = new Video(
            title: 'Ne',
            description: 'Ne',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
        );

    }
}
