<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Notification\Services\WeatherNotificationService;
use App\Domains\Notification\Strategies\PrecipitationStrategy;
use App\Domains\Notification\Strategies\UVIndexStrategy;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\Weather\Services\GeocodingServiceInterface;
use App\Domains\Weather\Services\WeatherServiceInterface;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(WeatherNotificationService::class, function ($app) {
            return new WeatherNotificationService(
                $app->make(WeatherServiceInterface::class),
                $app->make(GeocodingServiceInterface::class),
                $app->make(UserRepositoryInterface::class),
                [
                    $app->make(PrecipitationStrategy::class),
                    $app->make(UVIndexStrategy::class),
                ]
            );
        });
    }
}
