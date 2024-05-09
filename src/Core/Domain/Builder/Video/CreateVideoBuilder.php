<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;

class CreateVideoBuilder implements Builder
{
    protected ?Video $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    private function reset(): void
    {
        $this->entity = null;
    }

    public function createEntity(object $input): Builder
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating
        );

        $this->addIds($input);

        return $this;
    }

    public function addIds(object $input): void
    {
        // Inserir as Categories no Video
        foreach ($input->categories as $categoryId) {
            $this->entity->addCategoryId($categoryId);
        }

        // Inserir os Genres no Video
        foreach ($input->genres as $genreId) {
            $this->entity->addGenreId($genreId);
        }

        // Inserir os Cast Members no Video
        foreach ($input->castMembers as $castMemberId) {
            $this->entity->addCastMemberId($castMemberId);
        }

    }

    public function addMediaVideo(string $path, MediaStatus $status, string $encodedPath = ''): Builder
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: $status,
            encodedPath: $encodedPath
        );
        $this->entity->setVideoFile($media);

        return $this;
    }

    public function addTrailer(string $path): Builder
    {
        $this->entity->setTrailerFile(new Media(
            filePath: $path,
            mediaStatus: MediaStatus::COMPLETE,
        ));

        return $this;
    }

    public function addThumb(string $path): Builder
    {
        $this->entity->setThumbFile(new Image($path));

        return $this;
    }
    
    public function addHalfThumb(string $path): Builder
    {
        //dump($path);
        $this->entity->setThumbHalfFile(new Image($path));

        return $this;
    }

    public function addBanner(string $path): Builder
    {
        $this->entity->setBannerFile(new Image($path));

        return $this;
    }
    
    public function getEntity(): Video
    {
        return $this->entity;
    }

}