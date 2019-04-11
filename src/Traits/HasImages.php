<?php

namespace Submtd\LaravelModelImages\Traits;

use Illuminate\Http\UploadedFile;
use Submtd\LaravelModelImages\Models\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Image\Image as SpatieImage;
use Spatie\Image\Manipulations;

trait HasImages
{
    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function addImage(UploadedFile $file, int $weight = 0)
    {
        // validate file
        $allowedMimes = config('laravel-model-images.allowedMimes', 'jpeg,png,jpg,gif,svg');
        $maxFileSize = config('laravel-model-images.maxFileSize', 10240);
        $validator = Validator::make(['file' => $file], [
            'file' => "required|image|mimes:$allowedMimes|max:$maxFileSize",
        ]);
        if ($validator->fails()) {
            throw new \Exception('Invalid file.', 400);
        }
        // make a unique name
        $imageName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $sizes = config('laravel-model-images.sizes', ['original' => ['path' => 'original']]);
        foreach (array_keys($sizes) as  $size) {
            $file->move($this->getImagePath($imageName, $size));
            $this->resizeImage($imageName, $size);
        }
        // save image model
        $this->images->create([
            'image' => $imageName,
            'weight' => $weight,
        ]);
    }

    protected function getImagePath(string $imageName, string $size)
    {
        $size = config('laravel-model-images.sizes.' . $size, []);
        return config('laravel-model-images.imagePath', 'images') . (isset($size['path']) ? '/' . $size['path'] : '') . '/' . $imageName;
    }

    protected function getImageUrl(string $imageName, string $size, bool $secure = false)
    {
        if ($secure) {
            return secure_url($this->getImagePath($imageName, $size));
        }
        return url($this->getImagePath($imageName, $size));
    }

    protected function resizeImage(string $imageName, string $size)
    {
        $image = SpatieImage::load($this->getImagePath($imageName, $size));
        $size = config('laravel-model-images.sizes.' . $size, []);
        if (isset($size['fit']) && isset($size['width']) && isset($size['height'])) {
            switch ($size['fit']) {
                case 'crop':
                    $image->fit(Manipulations::FIT_CROP, $size['width'], $size['height']);
                    break;
                case 'stretch':
                    $image->fit(Manipulations::FIT_STRETCH, $size['width'], $size['height']);
                    break;
                case 'fill':
                    $image->fit(Manipulations::FIT_FILL, $size['width'], $size['height']);
                    $image->background(isset($size['background']) ? $size['background'] : config('laravel-model-images.imageBackground', '000000'));
                    break;
                case 'max':
                    $image->fit(Manipulations::FIT_MAX, $size['width'], $size['height']);
                    break;
                case 'contain':
                    $image->fit(Manipulations::FIT_CONTAIN, $size['width'], $size['height']);
                    break;
            }
        }
        if (config('laravel-model-images.optimizeImage', true)) {
            $image->optimize();
        }
        $image->save();
    }
}
