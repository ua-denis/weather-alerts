<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domains\Notification\Services\WeatherNotificationService;
use App\Events\UserRegistered;

class SendWeatherNotificationsOnUserRegistration
{
    private WeatherNotificationService $weatherNotificationService;

    public function __construct(WeatherNotificationService $weatherNotificationService)
    {
        $this->weatherNotificationService = $weatherNotificationService;
    }

    public function handle(UserRegistered $event): void
    {
        $this->weatherNotificationService->notifyUsers($event->user->city);
    }
}
