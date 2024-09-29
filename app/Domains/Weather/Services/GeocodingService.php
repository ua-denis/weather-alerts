<?php

declare(strict_types=1);

namespace App\Domains\Weather\Services;

use App\Domains\Weather\ValueObjects\Coordinates;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeocodingService implements GeocodingServiceInterface
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.geocoding_key');
    }

    /**
     * @throws Exception
     */
    public function getCoordinates(string $city): Coordinates
    {
        return cache()->remember("geocoding.{$city}", 3600, function () use ($city) {
            return $this->fetchCoordinates($city);
        });
    }

    /**
     * @param  string  $city
     * @return Coordinates
     * @throws RuntimeException
     */
    private function fetchCoordinates(string $city): Coordinates
    {
        $response = Http::get('http://api.openweathermap.org/geo/1.0/direct', [
            'q' => $city,
            'limit' => 1,
            'appid' => $this->apiKey,
        ]);

        if ($response->failed() || empty($response->json())) {
            Log::error("Geocoding failed for city: {$city}");
            throw new RuntimeException("Unable to retrieve coordinates for city: {$city}");
        }

        return $this->mapToCoordinates($response->json()[0]);
    }

    /**
     * @param  array  $data
     * @return Coordinates
     */
    private function mapToCoordinates(array $data): Coordinates
    {
        return new Coordinates(
            latitude: $data['lat'],
            longitude: $data['lon']
        );
    }
}
