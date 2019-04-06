<?php

namespace Submtd\LaravelModelImages\Models;

use Illuminate\Database\Eloquent\Model;

class ImageVariation extends Model
{
    protected $fillable = [
        'variation',
        'image_path',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
