<?php

declare(strict_types=1);

namespace App\Domains\Notification\Strategies;

use App\Domains\Weather\Entities\WeatherData;

class UVIndexStrategy implements NotificationStrategyInterface
{
    private const UV_INDEX_THRESHOLD = 7;

    public function shouldNotify(WeatherData $weatherData): bool
    {
        return $weatherData->uvIndex > self::UV_INDEX_THRESHOLD;
    }

    public function type(): string
    {
        return 'High UV Index';
    }

    public function message(): string
    {
        return 'Alert: Harmful UV rays expected. Please take necessary precautions.';
    }
}
