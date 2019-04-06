<?php

namespace Submtd\LaravelModelImages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Image extends Model
{
    protected $fillable = [
        'title',
        'description',
        'weight',
    ];

    protected $with = [
        'variations',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('imagesByWeight', function (Builder $builder) {
            $builder->orderBy('weight')->orderBy('created_at');
        });
    }

    public function variations()
    {
        return $this->hasMany(ImageVariation::class);
    }

    public function delete() {
        foreach($this->variations as $variation) {
            @unlink(storage_path('app/public') . '/' . $variation->image_path);
            $variation->delete();
        }
        parent::delete();
    }

}
