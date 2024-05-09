<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Builder\Video\{
    Builder,
    CreateVideoBuilder
};
use Core\UseCase\Video\BaseVideoUseCase;
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Throwable;

class CreateVideoUseCase extends BaseVideoUseCase
{
    //protected EntityVideo $entity;
    
    // Movido para a BaseClass
    /*
    protected BuilderVideo $builder;

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
        protected CategoryRepositoryInterface $categoryRepository,
        protected GenreRepositoryInterface $genreRepository,
        protected CastMemberRepositoryInterface $castMemberRepository
    )
    {
        $this->builder = new BuilderVideo();
    }
    */

    protected function getBuilder(): Builder
    {
        return new CreateVideoBuilder;
    }

    public function exec(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {
        // Validar os IDs recebidos
        $this->validateAllIds($input);

        //$this->entity = $this->createEntity($input);

        $this->builder->createEntity($input);

        try {

            // Inserir o Vídeo no repositório - Persistir
            //$this->repository->insert($this->entity);
            $this->repository->insert($this->builder->getEntity());

            // storage do Video, usando o $id, gera evento de Video criado
            $this->storeFiles($input);

            //$this->repository->updateMedia($this->entity);
            $this->repository->updateMedia($this->builder->getEntity());

            $this->transaction->commit();

            //return $this->output($this->entity);
            return $this->output();

        } catch (Throwable $th) {
            $this->transaction->rollback();

            // if (isset($pathMedia)) $this->storage->delete($pathMedia);

            throw $th;
        }

    }

    /* Substiutuito pelo Builder
    private function createEntity(CreateInputVideoDTO $input): EntityVideo{

        // Criar de video a partir do Input recebido
        $video = new EntityVideo(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating
        );

        // Inserir as Categories no Video - Validar
        //$this->validateCategoriesId($input->categories);
        foreach ($input->categories as $categoryId) {
            $video->addCategoryId($categoryId);
        }

        // Inserir os Genres no Video - Validar
        //$this->validateGenresId($input->genres);
        foreach ($input->genres as $genreId) {
            $video->addGenreId($genreId);
        }

        // Inserir os Cast Members no Video - Validar
        //$this->validateCastMembersId($input->castMembers);
        foreach ($input->castMembers as $castMemberId) {
            $video->addCastMemberId($castMemberId);
        }

        return $video;
    }
    */

    // Movido para a BaseClass
    /*
    protected function storeFiles(object $input): void
    {

        $path = $this->builder->getEntity()->id();
        //if ($pathVideoFile = $this->storeFile($this->entity->id(), $input->videoFile)){
        if ($pathVideoFile = $this->storeFile($path, $input->videoFile)){
            // $media = new Media(
            //     filePath: $pathVideoFile,
            //     mediaStatus: MediaStatus::PROCESSING,
            // );
            // $this->entity->setVideoFile($media);

            $this->builder->addMediaVideo($pathVideoFile, MediaStatus::PROCESSING);

            //$this->eventManager->dispatch(new VideoCreatedEvent($this->entity));
            $this->eventManager->dispatch(new VideoCreatedEvent($this->builder->getEntity()));
        }

        //if ($pathTrailerFile = $this->storeFile($this->entity->id(), $input->trailerFile)){
        if ($pathTrailerFile = $this->storeFile($path, $input->trailerFile)){
            // $this->entity->setTrailerFile(new Media(
            //             filePath: $pathTrailerFile,
            //             mediaStatus: MediaStatus::COMPLETE,
            // ));
            $this->builder->addTrailer($pathTrailerFile);
        }

        //if ($pathBannerFile = $this->storeFile($this->entity->id(), $input->bannerFile)){
        if ($pathBannerFile = $this->storeFile($path, $input->bannerFile)){
            //$this->entity->setBannerFile(new Image($pathBannerFile));
            $this->builder->addBanner($pathBannerFile);
        }

        //if ($pathThumbFile = $this->storeFile($this->entity->id(), $input->thumbFile)){
        if ($pathThumbFile = $this->storeFile($path, $input->thumbFile)){
            //$this->entity->setThumbFile(new Image($pathThumbFile));
            $this->builder->addThumb($pathThumbFile);
        }

        //if ($pathThumbHalfFile = $this->storeFile($this->entity->id(), $input->thumbHalf)){
        if ($pathThumbHalfFile = $this->storeFile($path, $input->thumbHalf)){
            //$this->entity->setThumbHalfFile(new Image($pathThumbHalfFile));
            $this->builder->addHalfThumb($pathThumbHalfFile);
        }

    }
    */
    
    // Movido para a BaseClass
    /*
    private function storeFile(string $path, ?array $media = null): null|string
    {
        if ($media){
            return $this->storage->store(
                path: $path, 
                file: $media
            );
        }

        return null;
    }
    */

    /*
    private function validateCategoriesId(array $categoriesId = []): void
    {
       $categoriesDb =  $this->categoryRepository->getIdsListIds($categoriesId);

       $arrayDiff = array_diff($categoriesId, $categoriesDb);

       if (count($arrayDiff) > 0) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) === 1 ? 'Category' : 'Categories',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
       }
    }

    private function validateGenresId(array $genresId = []): void
    {
       $genresDb =  $this->genreRepository->getIdsListIds($genresId);

       $arrayDiff = array_diff($genresId, $genresDb);

       if (count($arrayDiff) > 0) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) === 1 ? 'Genre' : 'Genres',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
       }
    }

    private function validateCastMembersId(array $castMembersId = []): void
    {
       $castMembersDb =  $this->castMemberRepository->getIdsListIds($castMembersId);

       $arrayDiff = array_diff($castMembersId, $castMembersDb);

       if (count($arrayDiff) > 0) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) === 1 ? 'Cast Member' : 'Cast Members',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
       }
    }
    */

    // Movido para a BaseClass
    /*
    protected function validateAllIds(object $input): void
    {
        $this->validateIds($input->categories, $this->categoryRepository, 'Category', 'Categories');
        $this->validateIds($input->genres, $this->genreRepository, 'Genre');
        $this->validateIds($input->castMembers, $this->castMemberRepository, 'Cast Member');

    }

    protected function validateIds(array $ids = [], $repository, string $singLbl, ?string $plurLbl = null): void
    {
        $idsDb =  $repository->getIdsListIds($ids);

        $arrayDiff = array_diff($ids, $idsDb);
 
        if (count($arrayDiff) > 0) {
             $msg = sprintf(
                 '%s %s not found',
                 count($arrayDiff) > 1 ? $plurLbl ?? $singLbl . 's' :  $singLbl,
                 implode(', ', $arrayDiff)
             );
             throw new NotFoundException($msg);
        }
 
    }
    */

    //private function output(EntityVideo $video): CreateOutputVideoDTO
    private function output(): CreateOutputVideoDTO
    {
        $video = $this->builder->getEntity();
        return new CreateOutputVideoDTO(
            id: $video->id(),
            title: $video->title,
            description: $video->description,
            yearLaunched: $video->yearLaunched,
            duration: $video->duration,
            opened: $video->opened,
            rating: $video->rating,
            createdAt: $video->createdAt(),
            categories: $video->categoriesId,
            genres: $video->genresId,
            castMembers: $video->castMembersIds,
            videoFile: $video->videoFile()?->filePath,
            trailerFile: $video->trailerFile()?->filePath,
            thumbFile: $video->thumbFile()?->path(),
            thumbHalf: $video->thumbHalf()?->path(),
            bannerFile: $video->bannerFile()?->path()
        );

    }

}
