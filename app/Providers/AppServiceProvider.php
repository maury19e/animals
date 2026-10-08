<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\AnimalServiceInterface;
use App\Services\SessionAnimalService;
use App\Contracts\SpeciesServiceInterface;
use App\Services\SQLSpeciesService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AnimalServiceInterface::class, SessionAnimalService::class);
        $this->app->singleton(SpeciesServiceInterface::class, SQLSpeciesService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
