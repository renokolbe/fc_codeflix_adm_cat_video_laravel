<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\Factory\VideoValidatorFactory;
use Core\Domain\Notification\Notification;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video extends Entity{

    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersIds = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
        protected ?DateTime $createdAt = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
    ){
        parent::__construct();
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
        $this->validate();
    }

    public function update(string $title, string $description): void
    {
        $this->title = $title;
        $this->description = $description;
        
        $this->validate();
    }

    public function addCategoryId(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        $key = array_search($categoryId, $this->categoriesId);
        if ($key !== false) {
            unset($this->categoriesId[$key]);
        }
    }

    public function addGenreId(string $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenreId(string $genreId)
    {
        $key = array_search($genreId, $this->genresId);
        if ($key !== false) {
            unset($this->genresId[$key]);
        }
    }

    public function addCastMemberId(string $castMemberId)
    {
        array_push($this->castMembersIds, $castMemberId);
    }

    public function removeCastMemberId(string $castMemberId)
    {
        $key = array_search($castMemberId, $this->castMembersIds);
        if ($key !== false) {
            unset($this->castMembersIds[$key]);
        }
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function setThumbFile(Image $thumbFile): void
    {
        $this->thumbFile = $thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function setThumbHalfFile(Image $thumbHalfFile): void
    {
        $this->thumbHalf = $thumbHalfFile;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function setBannerFile(Image $bannerFile): void
    {
        $this->bannerFile = $bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function setTrailerFile(Media $trailerFile): void
    {
        $this->trailerFile = $trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    public function setVideoFile(Media $videoFile): void
    {
        $this->videoFile = $videoFile;
    }

    protected function validate()
    {
        VideoValidatorFactory::create()->validate($this);
        
    /*        
        $this->notification = new Notification();

        if (empty($this->title)) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'title should not be empty or null',
            ]);
        }

        if (strlen($this->title) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'title must be greater than 3 characters',
            ]);
        }

        if (strlen($this->title) > 255) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'title must be not greater than 255 characters',
            ]);
        }

        if ( !empty($this->description) && strlen($this->description) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'description must be greater than 3 characters',
            ]);
        }
    */        

        if ($this->notification->hasErrors()) {
            throw new NotificationException(
                $this->notification->messages('video')
            );
        }
    }
}