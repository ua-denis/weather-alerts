<?php

declare(strict_types=1);

namespace App\Domains\Weather\Services;

use App\Domains\Weather\Entities\WeatherData;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class OpenWeatherMapService implements WeatherServiceInterface
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.one_call_key');
    }

    /**
     * @throws Exception
     */
    public function getWeatherData(float $lat, float $lon): WeatherData
    {
        $response = $this->fetchWeatherData($lat, $lon);

        return $this->handleResponse($response, $lat, $lon);
    }

    /**
     * @param  float  $lat
     * @param  float  $lon
     * @return mixed
     */
    private function fetchWeatherData(float $lat, float $lon): mixed
    {
        return Http::get('https://api.openweathermap.org/data/2.5/onecall', [
            'lat' => $lat,
            'lon' => $lon,
            'exclude' => 'minutely,hourly,daily,alerts',
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);
    }

    /**
     * @param  mixed  $response
     * @param  float  $lat
     * @param  float  $lon
     * @return WeatherData
     * @throws RuntimeException
     */
    private function handleResponse($response, float $lat, float $lon): WeatherData
    {
        if ($response->failed()) {
            Log::error("Weather data retrieval failed for coordinates: {$lat}, {$lon}");
            throw new RuntimeException('Unable to retrieve weather data.');
        }

        return $this->mapToWeatherData($response->json());
    }

    /**
     * @param  array  $data
     * @return WeatherData
     */
    private function mapToWeatherData(array $data): WeatherData
    {
        return new WeatherData(
            precipitation: $data['current']['rain']['1h'] ?? 0,
            uvIndex: $data['current']['uvi'] ?? 0
        );
    }
}
