<?php

declare(strict_types=1);

namespace App\Domains\Weather\Services;

use App\Domains\Weather\Entities\WeatherData;

interface WeatherServiceInterface
{
    public function getWeatherData(float $lat, float $lon): WeatherData;
}
