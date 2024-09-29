<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\User\ValueObjects\Email;
use App\Domains\Weather\Entities\WeatherData;
use App\Domains\Weather\Services\GeocodingServiceInterface;
use App\Domains\Weather\Services\WeatherAlertService;
use App\Domains\Weather\Services\WeatherServiceInterface;
use App\Domains\Weather\ValueObjects\Coordinates;
use App\Events\NotificationCreated;
use App\Models\User as EloquentUser;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Random\RandomException;
use Tests\TestCase;

class WeatherAlertServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException|RandomException
     */
    public function test_notifications_are_sent_when_conditions_met(): void
    {
        Event::fake();

        EloquentUser::factory()->create([
            'email' => (new Email('test@example.com'))->value(),
            'city' => 'London',
        ]);

        // Mock GeocodingService
        $coordinates = new Coordinates(51.5074, -0.1278);
        $geocodingServiceMock = Mockery::mock(GeocodingServiceInterface::class);
        $geocodingServiceMock->shouldReceive('getCoordinates')->with('London')->andReturn($coordinates);
        $this->app->instance(GeocodingServiceInterface::class, $geocodingServiceMock);

        // Mock WeatherService
        $weatherData = new WeatherData(60.0, 8.0);
        $weatherServiceMock = Mockery::mock(WeatherServiceInterface::class);
        $weatherServiceMock->shouldReceive('getWeatherData')->with(51.5074, -0.1278)->andReturn($weatherData);
        $this->app->instance(WeatherServiceInterface::class, $weatherServiceMock);

        // Execute the service
        $weatherAlertService = $this->app->make(WeatherAlertService::class);
        $weatherAlertService->checkAndNotify();

        Event::assertDispatchedTimes(NotificationCreated::class, 2);
    }

    public function test_geocoding_fails_for_unknown_city(): void
    {
        Event::fake();

        EloquentUser::factory()->create([
            'email' => (new Email('valid@example.com'))->value(),
            'city' => 'UnknownCity',
        ]);

        // Mock GeocodingService to throw an exception for 'UnknownCity'
        $geocodingServiceMock = Mockery::mock(GeocodingServiceInterface::class);
        $geocodingServiceMock->shouldReceive('getCoordinates')
            ->with('UnknownCity')
            ->andThrow(new Exception('City not found'));
        $this->app->instance(GeocodingServiceInterface::class, $geocodingServiceMock);

        // Mock WeatherService to ensure it's not called when geocoding fails
        $weatherServiceMock = Mockery::mock(WeatherServiceInterface::class);
        $weatherServiceMock->shouldNotReceive('getWeatherData');
        $this->app->instance(WeatherServiceInterface::class, $weatherServiceMock);

        // Execute the service
        $weatherAlertService = $this->app->make(WeatherAlertService::class);
        $weatherAlertService->checkAndNotify();

        Event::assertNotDispatched(NotificationCreated::class);
    }

}
