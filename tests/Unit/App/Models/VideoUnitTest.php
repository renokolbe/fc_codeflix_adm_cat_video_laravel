<?php

namespace Tests\Unit\App\Models;

use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Video();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }

    protected function fillables(): array
    {
        return [
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'duration',
            'rating',
            'created_at',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'opened' => 'boolean',
            'deleted_at' => 'datetime',  // Default do Laravel
        ];
    }

}
