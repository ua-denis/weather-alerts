<?php

declare(strict_types=1);

namespace App\Domains\Notification\Services;

use App\Domains\Notification\Entities\Notification;
use App\Domains\Notification\Strategies\NotificationStrategyInterface;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\Weather\Services\GeocodingServiceInterface;
use App\Domains\Weather\Services\WeatherServiceInterface;
use Exception;
use RuntimeException;

class WeatherNotificationService
{
    private WeatherServiceInterface $weatherService;
    private GeocodingServiceInterface $geocodingService;
    private UserRepositoryInterface $userRepository;
    private array $notificationStrategies;

    public function __construct(
        WeatherServiceInterface $weatherService,
        GeocodingServiceInterface $geocodingService,
        UserRepositoryInterface $userRepository,
        array $notificationStrategies
    ) {
        $this->weatherService = $weatherService;
        $this->geocodingService = $geocodingService;
        $this->userRepository = $userRepository;
        $this->notificationStrategies = $notificationStrategies;
    }

    /**
     * @throws Exception
     */
    public function notifyUsers(string $city): void
    {
        $users = $this->userRepository->getAll();

        $coordinates = $this->geocodingService->getCoordinates($city);
        if (!$coordinates) {
            throw new RuntimeException("Unable to retrieve coordinates for the city: $city");
        }

        $weatherData = $this->weatherService->getWeatherData($coordinates->latitude, $coordinates->longitude);

        foreach ($users as $user) {
            $this->notifyUserBasedOnStrategies($user, $weatherData);
        }
    }

    private function notifyUserBasedOnStrategies($user, $weatherData): void
    {
        foreach ($this->notificationStrategies as $strategy) {
            if ($strategy->shouldNotify($weatherData)) {
                $notification = $this->createNotification($user, $strategy, $weatherData);
                $notification->send();
            }
        }
    }

    private function createNotification($user, NotificationStrategyInterface $strategy, $weatherData): Notification
    {
        return new Notification($user, $strategy->type(), $strategy->message($weatherData));
    }
}
