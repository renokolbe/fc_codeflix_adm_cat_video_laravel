<?php

namespace App\Repositories\Eloquent;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use App\Models\Video as Model;
use App\Repositories\Eloquent\Traits\VideoTrait;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use Core\Domain\Entity\Entity;
use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Image as ValueObjectImage;
use Core\Domain\ValueObject\Media as ValueObjectMedia;
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    use VideoTrait;

    public function __construct(
        protected Model $model
    ) {
    }

    public function insert(Entity $entity): Entity
    {
        $video = $this->model->create([
            'id' => $entity->id,
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'opened' => $entity->opened,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'created_at' => $entity->createdAt(),
        ]);

        $this->syncRelationShips($video, $entity);

        return $this->convertObjectToVideo($video);

    }

    public function findById(string $id): Entity
    {
        if (! $video = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $this->convertObjectToVideo($video);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $videos = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('title', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('title', $order)
            ->get();

        return $videos->toArray();

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $paginator = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('title', 'LIKE', "%{$filter}%");
                }
            })
                        // Quais os dados relacionados que devem ser retornados
            ->with([
            'media',
            'trailer',
            'banner',
            'thumb',
            'thumbHalf',
            'categories',
            'castMembers',
            'genres',
            ])
            ->orderBy('title', $order)
            ->paginate($totalPage, ['*'], 'page', $page);

        return new PaginationPresenter($paginator);

    }

    public function update(Entity $entity): Entity
    {
        if (! $videoDb = $this->model->find($entity->id)) {
            throw new NotFoundException('Video not found');
        }

        $videoDb->update([
            'title' => $entity->title,
            'description' => $entity->description,
        ]);

        $this->syncRelationShips($videoDb, $entity);

        $videoDb->refresh();

        return $this->convertObjectToVideo($videoDb);
    }

    public function delete(string $id): bool
    {
        if (! $videoDb = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $videoDb->delete();

    }

    public function updateMedia(Entity $entity): Entity
    {
        if (! $videoDb = $this->model->find($entity->id)) {
            throw new NotFoundException('Video not found');
        }

        $this->updateMediaVideo($entity, $videoDb);

        // Substituito pela Método updateMediaTrailer da Trait VideoTrait
        // if ($trailer = $entity->trailerFile()){
        //     $action = $videoDb->trailer()->exists() ? 'update' : 'create';
        //     //var_dump($action);
        //     $videoDb->trailer()->{$action}([
        //         'file_path' => $trailer->filePath,
        //         'media_status' => $trailer->mediaStatus->value,
        //         'encoded_path' => $trailer->encodedPath,
        //         'type' => MediaTypes::TRAILER->value
        //     ]);

        //     // O Metodo UpdateOrCreate pode acabar criando Novos registros entao deve-se evitar usá-lo
        //     // $videoDb->trailer()->UpdateOrCreate([
        //     //     'file_path' => $trailer->filePath,
        //     //     'media_status' => $trailer->mediaStatus->value,
        //     //     'encoded_path' => $trailer->encodedPath,
        //     //     'type' => MediaTypes::TRAILER->value
        //     // ]);
        // }

        $this->updateMediaTrailer($entity, $videoDb);

        // Substituito pela Método updateImageBanner da Trait VideoTrait
        // if ($banner = $entity->bannerFile()) {
        //     $action = $videoDb->banner()->exists() ? 'update' : 'create';
        //     $videoDb->banner()->{$action}([
        //         'path' => $banner->path(),
        //         'type' => ImageTypes::BANNER->value
        //     ]);
        // }

        $this->updateImageBanner($entity, $videoDb);

        $this->updateImageThumb($entity, $videoDb);

        $this->updateImageThumbHalf($entity, $videoDb);

        $videoDb->refresh();

        return $this->convertObjectToVideo($videoDb);

    }

    private function syncRelationShips(Model $video, EntityVideo $entity)
    {
        if (count($entity->categoriesId) > 0) {
            $video->categories()->sync($entity->categoriesId);
        }

        if (count($entity->genresId) > 0) {
            $video->genres()->sync($entity->genresId);
        }

        if (count($entity->castMembersIds) > 0) {
            $video->castMembers()->sync($entity->castMembersIds);
        }

    }

    private function convertObjectToVideo(object $object): EntityVideo
    {
        $entity = new EntityVideo(
            id: new Uuid($object->id),
            title: $object->title,
            description: $object->description,
            yearLaunched: $object->year_launched,
            opened: $object->opened,
            rating: Rating::from($object->rating),
            duration: $object->duration,
            createdAt: $object->created_at
        );

        foreach ($object->categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($object->genres as $genre) {
            $entity->addGenreId($genre->id);
        }

        foreach ($object->castMembers as $castMember) {
            $entity->addCastMemberId($castMember->id);
        }

        $builder = (new UpdateVideoBuilder())->setEntity($entity);

        if ($media = $object->media) {

            // Subsittuido pelo Builder
            // $entity->setVideoFile(
            //     new ValueObjectMedia(
            //         filePath: $media->file_path,
            //         mediaStatus: MediaStatus::from($media->media_status),
            //         encodedPath: $media->encoded_path
            //     )
            // );
            $builder->addMediaVideo(
                path: $media->file_path,
                status: MediaStatus::from($media->media_status),
                encodedPath: $media->encoded_path
            );
        }

        if ($trailer = $object->trailer) {
            // Subsittuido pelo Builder
            // $entity->setTrailerFile(
            //     new ValueObjectMedia(
            //         filePath: $trailer->file_path,
            //         mediaStatus: MediaStatus::from($trailer->media_status),
            //         encodedPath: $trailer->encoded_path
            //     )
            // );
            $builder->addTrailer($trailer->file_path);
        }

        if ($banner = $object->banner) {
            // Subsittuido pelo Builder
            // $entity->setBannerFile(
            //     new ValueObjectImage(
            //         path: $banner->path,
            //     )
            // );
            $builder->addBanner($banner->path);
        }

        if ($thumb = $object->thumb) {
            // Subsittuido pelo Builder
            // $entity->setThumbFile(
            //     new ValueObjectImage(
            //         path: $thumb->path,
            //     )
            // );
            $builder->addThumb($thumb->path);
        }

        if ($thumbHalf = $object->thumbHalf) {
            // Subsittuido pelo Builder
            // $entity->setThumbHalfFile(
            //     new ValueObjectImage(
            //         path: $thumbHalf->path,
            //     )
            // );
            $builder->addHalfThumb($thumbHalf->path);
        }

        // Subsittuido pelo Builder
        // return $entity;
        return $builder->getEntity();
    }
}
