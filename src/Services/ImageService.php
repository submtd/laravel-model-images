<?php

namespace Submtd\LaravelModelImages\Services;

use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;

class ImageService
{
    public $file;

    public $image;

    public $filename;

    public $width;

    public $height;

    public $fit;

    public function __construct($file, string $filename, int $width = 0, int $height = 0, string $fit = 'original')
    {
        $this->file = $file;
        $this->filename = $filename;
        $this->width = $width;
        $this->height = $height;
        $this->fit = $fit;
        $this->image = Image::load($this->file);
    }

    public static function saveImage($file, $filename, int $width = 0, int $height = 0, string $fit = 'original')
    {
        $imageService = new static($file, $filename, $width, $height, $fit);
        if(!method_exists($imageService, 'resize'.$fit)) {
            throw new \Exception('Unknown fit method.', 400);
        }
        call_user_func([$imageService, 'resize'.$fit]);
        if(config('laravel-model-images.optimizeImage', true)) {
            $imageService->optimize();
        }
        $imageService->save();
        return $imageService->filename;
    }

    public function optimize() {
        $this->image->optimize();
    }

    public function save() {
        $this->image->save(storage_path('app/public') . '/' . $this->filename);
    }

    public function resizeoriginal() {
        return;
    }

    public function resizecontain() {
        $this->image->fit(Manipulations::FIT_CONTAIN, $this->width, $this->height);
    }

    public function resizemax() {
        $this->image->fit(Manipulations::FIT_MAX, $this->width, $this->height);
    }

    public function resizefill() {
        $this->image->fit(Manipulations::FIT_FILL, $this->width, $this->height)
            ->background(config('laravel-model-images.imageBackground', '000000'));
    }

    public function resizestretch() {
        $this->image->fit(Manipulations::FIT_STRETCH, $this->width, $this->height);
    }

    public function resizecrop() {
        $this->image->fit(Manipulations::FIT_CROP, $this->width, $this->height);
    }
}
