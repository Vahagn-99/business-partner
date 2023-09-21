<?php

namespace App\Providers;

use App\Services\Files\FileManager;
use App\Services\Files\FileManagerInterface;
use Illuminate\Support\ServiceProvider;

class CustomServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileManagerInterface::class, FileManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
