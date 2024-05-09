<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        //return $this->resource;
        return collect($this->resource)
            ->mapWithKeys(function ($value, $key) {

                // Converte o nome da Chave do Padrão camelCase para snake_case
                $key = trim(strtolower(preg_replace('/[A-Z]/', '_$0', $key)));

                return [
                    $key => $value,
                ];
            });

    }
}
