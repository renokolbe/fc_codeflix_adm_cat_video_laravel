<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'medias_video';

    protected $fillable = [
        'file_path',
        'encoded_path',
        'media_status',
        'type',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // transformado em Trait - UuidTrait
    // /**
    //  * The "booted" method of the model.
    //  * 
    //  * @return void
    //  * 
    //  */
    // protected static function booted()
    // {
    //     static::creating(function ($model) {
    //         $model->id = Str::uuid();
    //     });
    // }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
