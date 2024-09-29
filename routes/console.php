<?php

use App\Domains\Notification\Services\WeatherNotificationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('weather:check-alerts')->hourly();
Schedule::call(function (WeatherNotificationService $weatherNotificationService) {
    $weatherNotificationService->notifyUsers('London');
})->hourly();
