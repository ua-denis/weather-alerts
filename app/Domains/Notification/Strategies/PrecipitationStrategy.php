<?php

declare(strict_types=1);

namespace App\Domains\Notification\Strategies;

use App\Domains\Weather\Entities\WeatherData;

class PrecipitationStrategy implements NotificationStrategyInterface
{
    private const PRECIPITATION_THRESHOLD = 50.0;

    public function shouldNotify(WeatherData $weatherData): bool
    {
        return $weatherData->precipitation > self::PRECIPITATION_THRESHOLD;
    }

    public function type(): string
    {
        return 'High Precipitation';
    }

    public function message(): string
    {
        return 'Alert: High levels of precipitation expected in your area.';
    }
}
