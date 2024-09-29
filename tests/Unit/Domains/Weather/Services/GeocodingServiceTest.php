<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Weather\Services;

use App\Domains\Weather\Services\GeocodingService;
use App\Domains\Weather\ValueObjects\Coordinates;
use Exception;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeocodingServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_get_coordinates_successfully(): void
    {
        Http::fake([
            'api.openweathermap.org/geo/1.0/direct*' => Http::response([
                [
                    'lat' => 51.5074,
                    'lon' => -0.1278,
                    'name' => 'London',
                    'country' => 'GB',
                ]
            ], 200)
        ]);

        $geocodingService = new GeocodingService();
        $coordinates = $geocodingService->getCoordinates('London');

        $this->assertInstanceOf(Coordinates::class, $coordinates);
        $this->assertEquals(51.5074, $coordinates->latitude);
        $this->assertEquals(-0.1278, $coordinates->longitude);
    }

    public function test_get_coordinates_failure(): void
    {
        Http::fake([
            'api.openweathermap.org/geo/1.0/direct*' => Http::response([], 404)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to retrieve coordinates for city: UnknownCity');

        $geocodingService = new GeocodingService();
        $geocodingService->getCoordinates('UnknownCity');
    }
}
