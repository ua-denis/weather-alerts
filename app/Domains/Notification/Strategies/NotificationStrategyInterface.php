<?php

declare(strict_types=1);

namespace App\Domains\Notification\Strategies;

use App\Domains\Weather\Entities\WeatherData;

interface NotificationStrategyInterface
{
    public function shouldNotify(WeatherData $weatherData): bool;

    public function type(): string;

    public function message(): string;
}
