<?php

declare(strict_types=1);

namespace App\Domains\Weather\Services;

use App\Domains\Notification\Entities\Notification;
use App\Domains\Notification\Strategies\PrecipitationStrategy;
use App\Domains\Notification\Strategies\UVIndexStrategy;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\User\ValueObjects\Email;
use App\Domains\Weather\Entities\WeatherData;
use Exception;
use Illuminate\Support\Facades\Log;

class WeatherAlertService
{
    private UserRepositoryInterface $userRepository;
    private WeatherServiceInterface $weatherService;
    private GeocodingServiceInterface $geocodingService;
    private array $notificationStrategies;

    public function __construct(
        UserRepositoryInterface $userRepository,
        WeatherServiceInterface $weatherService,
        GeocodingServiceInterface $geocodingService,
        array $notificationStrategies = []
    ) {
        $this->userRepository = $userRepository;
        $this->weatherService = $weatherService;
        $this->geocodingService = $geocodingService;

        $this->notificationStrategies = $notificationStrategies
            ?: [
                new PrecipitationStrategy(),
                new UVIndexStrategy(),
            ];
    }

    public function checkAndNotify(): void
    {
        $users = $this->userRepository->getAll();
        foreach ($users as $user) {
            $this->processUser($user);
        }
    }

    private function processUser($user): void
    {
        try {
            $coordinates = $this->geocodingService->getCoordinates($user->city());
            $weather = $this->weatherService->getWeatherData($coordinates->latitude, $coordinates->longitude);

            $this->notifyUser($user, $weather);
        } catch (Exception $e) {
            $this->logError($user, $e);
        }
    }

    private function notifyUser($user, WeatherData $weather): void
    {
        $notificationTypes = $this->determineNotifications($weather);

        foreach ($notificationTypes as $type => $message) {
            $this->createAndSendNotification($user, $type, $message);
        }
    }

    private function createAndSendNotification($user, string $type, string $message): void
    {
        $notification = new Notification($user, $type, $message);
        $notification->send();
    }

    private function determineNotifications(WeatherData $weather): array
    {
        $notifications = [];

        foreach ($this->notificationStrategies as $strategy) {
            if ($strategy->shouldNotify($weather)) {
                $notifications[$strategy->type()] = $strategy->message();
            }
        }

        return $notifications;
    }

    private function logError($user, Exception $e): void
    {
        $email = method_exists($user, 'email') && $user->email() instanceof Email
            ? $user->email()->value()
            : 'unknown email';

        Log::error("Error processing user: {$email}, Exception: {$e->getMessage()}", [
            'user_id' => $user->id,
            'exception' => $e
        ]);
    }
}
