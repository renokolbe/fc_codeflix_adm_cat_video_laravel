<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;
use Rakit\Validation\Validator;

class VideoRakitValidator implements ValidatiorInterface
{
    public function validate(Entity $entity): void
    {
        $data = $this->convertEntityForArray($entity);

        $validator = new Validator();

        $validation = $validator->validate($data, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($validation->fails()) {
            //dd($validation->errors->all());
            foreach ($validation->errors->all() as $error) {
                //dd($error);
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => $error,
                ]);
            }
        }
    }

    private function convertEntityForArray(Entity $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration,
        ];
    }
}
