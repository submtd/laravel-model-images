<?php

namespace Submtd\LaravelModelImages\Traits;

use Illuminate\Http\UploadedFile;
use Submtd\LaravelModelImages\Models\Image;
use Illuminate\Support\Str;
use Submtd\LaravelModelImages\Services\ImageService;

trait HasImages
{
    public function images()
    {
        return $this->morphMany(config('laravel-model-images.imageModel', Image::class), 'model');
    }

    public function addImage(UploadedFile $file, $title = null, $description = null, $weight = 0)
    {
        $image = $this->images()->create([
            'title' => $title,
            'description' => $description,
            'weight' => $weight,
        ]);
        foreach(config('laravel-model-images.sizes') as $sizeName => $sizeConfig) {
            $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $savedImage = ImageService::saveImage(
                $file->getPathname(), 
                $fileName,
                isset($sizeConfig['width']) ? $sizeConfig['width'] : 0,
                isset($sizeConfig['height']) ? $sizeConfig['height'] : 0,
                isset($sizeConfig['fit']) ? $sizeConfig['fit'] : 'original'
            );
            $image->variations()->create([
                'variation' => $sizeName,
                'image_path' => $savedImage,
            ]);
        }
    }
}
