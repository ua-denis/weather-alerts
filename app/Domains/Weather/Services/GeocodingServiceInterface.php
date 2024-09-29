<?php

declare(strict_types=1);

namespace App\Domains\Weather\Services;

use App\Domains\Weather\ValueObjects\Coordinates;

interface GeocodingServiceInterface
{
    public function getCoordinates(string $city): Coordinates;
}
