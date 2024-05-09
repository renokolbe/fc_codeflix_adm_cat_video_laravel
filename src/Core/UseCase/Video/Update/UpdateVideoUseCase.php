<?php

namespace Core\UseCase\Video\Update;

use Core\Domain\Builder\Video\Builder;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use Core\UseCase\Video\BaseVideoUseCase;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\DTO\UpdateOutputVideoDTO;
use Throwable;

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new UpdateVideoBuilder;
    }

    public function exec(UpdateInputVideoDTO $input): UpdateOutputVideoDTO
    {
        // Validar se o ID do Vídeo existe
        $entity = $this->repository->findById($input->id);

        $entity->update(
            title: $input->title,
            description: $input->description,

        );

        // Validar os IDs recebidos
        $this->validateAllIds($input);

        //$this->entity = $this->createEntity($input);

        $this->builder->setEntity($entity);

        // Atualizar com os Ids
        $this->builder->addIds($input);

        try {

            // Atualizar o Vídeo no repositório - Persistir
            //$this->repository->insert($this->entity);
            $this->repository->update($this->builder->getEntity());

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

    private function output(): UpdateOutputVideoDTO
    {
        $video = $this->builder->getEntity();

        return new UpdateOutputVideoDTO(
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
