<?php

declare(strict_types=1);

namespace App\Domains\Weather\ValueObjects;

final class Coordinates
{
    public float $latitude;
    public float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
