<?php

namespace Submtd\LaravelModelImages\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image',
        'weight',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
