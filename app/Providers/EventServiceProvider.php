<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\NotificationCreated;
use App\Events\UserRegistered;
use App\Listeners\SendEmailNotification;
use App\Listeners\SendWeatherNotificationsOnUserRegistration;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NotificationCreated::class => [
            SendEmailNotification::class,
        ],
        UserRegistered::class => [
            SendWeatherNotificationsOnUserRegistration::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
