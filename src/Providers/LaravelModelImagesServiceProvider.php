<?php

namespace Submtd\LaravelModelImages\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelModelImagesServiceProvider extends ServiceProvider
{
    /**
     * boot method
     */
    public function boot()
    {
        // config files
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-model-images.php', 'laravel-model-images');
        $this->publishes([__DIR__ . '/../../config' => config_path()], 'config');
        // set up migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->publishes([__DIR__ . '/../../database' => database_path()], 'migrations');
    }
}
