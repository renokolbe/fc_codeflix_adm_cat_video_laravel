<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use Core\Domain\Entity\Video as EntityVideo;
use Illuminate\Database\Eloquent\Model;

trait VideoTrait
{
    public function updateMediaVideo(EntityVideo $entity, Model $model): void
    {
        if ($media = $entity->videoFile()){
            $action = $model->media()->exists() ? 'update' : 'create';
            $model->media()->{$action}([
                'file_path' => $media->filePath,
                'media_status' => (string) $media->mediaStatus->value,
                'encoded_path' => $media->encodedPath,
                'type' => (string) MediaTypes::VIDEO->value
            ]);
        }
    }

    public function updateMediaTrailer(EntityVideo $entity, Model $model): void
    {
        if ($trailer = $entity->trailerFile()){
            $action = $model->trailer()->exists() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->filePath,
                'media_status' => (string) $trailer->mediaStatus->value,
                'encoded_path' => $trailer->encodedPath,
                'type' => (string) MediaTypes::TRAILER->value
            ]);
        }
    }

    public function updateImageBanner(EntityVideo $entity, Model $model): void
    {
        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->exists() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path(),
                'type' => (string) ImageTypes::BANNER->value
            ]);
        }
    }

    public function updateImageThumb(EntityVideo $entity, Model $model): void
    {
        if ($thumb = $entity->thumbFile()) {
            $action = $model->thumb()->exists() ? 'update' : 'create';
            $model->thumb()->{$action}([
                'path' => $thumb->path(),
                'type' => (string) ImageTypes::THUMB->value
            ]);
        }
    }

    public function updateImageThumbHalf(EntityVideo $entity, Model $model): void
    {
        if ($ThumbHalf = $entity->thumbHalf()) {
            $action = $model->ThumbHalf()->exists() ? 'update' : 'create';
            $model->ThumbHalf()->{$action}([
                'path' => $ThumbHalf->path(),
                'type' => (string) ImageTypes::THUMB_HALF->value
            ]);
        }
    }
}