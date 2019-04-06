<?php

namespace Submtd\LaravelModelImages\Models;

use Illuminate\Database\Eloquent\Model;

class ImageVariation extends Model
{
    protected $fillable = [
        'variation',
        'image_path',
    ];

    public static function boot() {
        parent::boot();
        static::deleting(function($model) {
            @unlink(storage_path('app/public') . '/' . $model->image_path);
        });
    }

    public function image()
    {
        return $this->belongsTo(config('laravel-model-images.imageModel', Image::class));
    }
}
