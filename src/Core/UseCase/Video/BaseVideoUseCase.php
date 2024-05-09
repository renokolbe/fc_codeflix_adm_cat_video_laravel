<?php

namespace Core\UseCase\Video;

use Core\Domain\Builder\Video\Builder;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

abstract class BaseVideoUseCase
{
    protected Builder $builder;

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
        protected CategoryRepositoryInterface $categoryRepository,
        protected GenreRepositoryInterface $genreRepository,
        protected CastMemberRepositoryInterface $castMemberRepository
    ) {
        $this->builder = $this->getBuilder();
    }

    abstract protected function getBuilder(): Builder;

    protected function storeFiles(object $input): void
    {
        $entity = $this->builder->getEntity();
        $path = $entity->id();
        if ($pathVideoFile = $this->storeFile($path, $input->videoFile)) {
            $this->builder->addMediaVideo($pathVideoFile, MediaStatus::PROCESSING);
            $this->eventManager->dispatch(new VideoCreatedEvent($entity));
        }

        if ($pathTrailerFile = $this->storeFile($path, $input->trailerFile)) {
            $this->builder->addTrailer($pathTrailerFile);
        }

        if ($pathBannerFile = $this->storeFile($path, $input->bannerFile)) {
            $this->builder->addBanner($pathBannerFile);
        }

        if ($pathThumbFile = $this->storeFile($path, $input->thumbFile)) {
            $this->builder->addThumb($pathThumbFile);
        }

        if ($pathThumbHalfFile = $this->storeFile($path, $input->thumbHalf)) {
            //dump($input->thumbHalf);
            //dump($pathThumbHalfFile);
            $this->builder->addHalfThumb($pathThumbHalfFile);
        }

    }

    protected function storeFile(string $path, ?array $media = null): ?string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media
            );
        }

        return null;
    }

    protected function validateAllIds(object $input): void
    {
        $this->validateIds($input->categories, $this->categoryRepository, 'Category', 'Categories');
        $this->validateIds($input->genres, $this->genreRepository, 'Genre');
        $this->validateIds($input->castMembers, $this->castMemberRepository, 'Cast Member');

    }

    protected function validateIds(array $ids, $repository, string $singLbl, ?string $plurLbl = null): void
    {
        $idsDb = $repository->getIdsListIds($ids);
        $arrayDiff = array_diff($ids, $idsDb);
        if (count($arrayDiff) > 0) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? $plurLbl ?? $singLbl.'s' : $singLbl,
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }
}
