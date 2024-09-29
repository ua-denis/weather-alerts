<?php

declare(strict_types=1);

namespace App\Domains\Weather\Entities;

final class WeatherData
{
    public float $precipitation;
    public float $uvIndex;

    public function __construct(float $precipitation, float $uvIndex)
    {
        $this->precipitation = $precipitation;
        $this->uvIndex = $uvIndex;
    }
}
