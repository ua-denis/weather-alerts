<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Notification\Strategies;

use App\Domains\Notification\Strategies\PrecipitationStrategy;
use App\Domains\Weather\Entities\WeatherData;
use PHPUnit\Framework\TestCase;

class PrecipitationStrategyTest extends TestCase
{
    public function test_should_notify_when_precipitation_above_threshold(): void
    {
        $weatherData = new WeatherData(60.0, 5);
        $strategy = new PrecipitationStrategy();

        $this->assertTrue($strategy->shouldNotify($weatherData));
    }

    public function test_should_not_notify_when_precipitation_below_threshold(): void
    {
        $weatherData = new WeatherData(40.0, 5);
        $strategy = new PrecipitationStrategy();

        $this->assertFalse($strategy->shouldNotify($weatherData));
    }
}
