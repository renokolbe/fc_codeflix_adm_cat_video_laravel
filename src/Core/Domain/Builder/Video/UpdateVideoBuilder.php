<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class UpdateVideoBuilder extends CreateVideoBuilder
{
    public function createEntity(object $input): Builder
    {
        $this->entity = new Video(
            id: new Uuid($input->id),
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
            createdAt: new DateTime($input->createdAt)
        );

        $this->addIds($input);

        return $this;
    }

    public function setEntity(Video $entity): Builder
    {
        $this->entity = $entity;

        return $this;
    }
}
