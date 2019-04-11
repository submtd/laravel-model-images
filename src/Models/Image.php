<?php

namespace Submtd\LaravelModelImages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = [
        'title',
        'description',
        'weight',
    ];

    protected $table = 'images';

    protected $with = [
        'variations',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('imagesByWeight', function (Builder $builder) {
            $builder->orderBy('weight')->orderBy('created_at');
        });
        static::deleting(function ($model) {
            foreach($model->variations as $variation) {
                $variation->delete();
            }
        });
    }

    public function variations()
    {
        return $this->hasMany(config('laravel-model-images.imageVariationModel', ImageVariation::class), 'image_id', 'id');
    }
}
