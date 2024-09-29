<?php

namespace App\Providers;

use App\Domains\User\Repositories\EloquentUserRepository;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\Weather\Services\GeocodingService;
use App\Domains\Weather\Services\GeocodingServiceInterface;
use App\Domains\Weather\Services\OpenWeatherMapService;
use App\Domains\Weather\Services\WeatherServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(WeatherServiceInterface::class, OpenWeatherMapService::class);
        $this->app->bind(GeocodingServiceInterface::class, GeocodingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
