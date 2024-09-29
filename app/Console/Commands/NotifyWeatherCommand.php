<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Notification\Services\WeatherNotificationService;
use Illuminate\Console\Command;

class NotifyWeatherCommand extends Command
{
    protected $signature = 'weather:notify {city}';
    protected $description = 'Send weather notifications based on current conditions';

    private WeatherNotificationService $weatherNotificationService;

    public function __construct(WeatherNotificationService $weatherNotificationService)
    {
        parent::__construct();
        $this->weatherNotificationService = $weatherNotificationService;
    }

    public function handle(): void
    {
        $city = $this->argument('city');
        $this->weatherNotificationService->notifyUsers($city);
        $this->info('Weather notifications sent successfully.');
    }
}
