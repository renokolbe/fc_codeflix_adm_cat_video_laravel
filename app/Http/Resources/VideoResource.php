<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'year_launched' => $this->year_launched ?? $this->yearLaunched,
            'rating' => $this->rating,
            'duration' => $this->duration,
            'opened' => $this->opened,
            'created_at' => Carbon::make($this->created_at ?? $this->createdAt)->format('Y-m-d H:i:s'),
            'categories' => $this->categories,
            'genres' => $this->genres,
            'cast_members' => $this->cast_members ?? $this->castMembers,
            'thumb' => $this->thumbFile ?? '',
            'banner' => $this->bannerFile ?? '',
            'trailer' => $this->trailerFile ?? '',
            'video' => $this->videoFile ?? '',
            'thumb_half' => $this->thumbHalfFile ?? ''
        ];
    }
}
