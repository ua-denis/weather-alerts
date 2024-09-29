## Introduction

This repository contains a modular system designed for weather-based notifications. It consists of several domains,
**Notification**, **User**, and **Weather**, and integrates various services like **Geocoding** and **OpenWeatherMap**
APIs.

The system follows clean architecture principles, ensuring modularity, scalability, and maintainability. Below is a
detailed breakdown of the entities, strategies, repositories, and services used in the system.

---

## Key Domains and Components

### 1. **Notification Domain**

#### **Notification Entity**

The `Notification` entity is responsible for handling notification creation and sending logic. It consists of the
following key elements:

- **User**: The recipient of the notification.
- **Type**: The type of notification (e.g., high UV, precipitation).
- **Message**: The content of the notification.
- **Send**: A method that logs the notification sending process and dispatches a `NotificationCreated` event.

#### **Notification Strategies**

This system follows the **Strategy Pattern** to define various weather-related notification strategies.

- **PrecipitationStrategy**: Notifies users when precipitation levels exceed a predefined threshold.
- **UVIndexStrategy**: Notifies users when the UV Index exceeds a predefined threshold.

Each strategy implements:

- **shouldNotify()**: Determines whether a notification should be sent.
- **type()**: Defines the type of notification.
- **message()**: Specifies the message to be delivered to the user.

---

### 2. **User Domain**

#### **User Entity**

The `User` entity models a system user. It contains:

- **ID**: The unique identifier for the user.
- **Email**: A value object for managing email addresses.
- **City**: The city associated with the user, used for weather-related notifications.

---

### 3. **Weather Domain**

#### **WeatherData Entity**

The `WeatherData` entity encapsulates relevant weather information:

- **Precipitation**: Current levels of precipitation.
- **UV Index**: The current UV index in the user's location.

#### **GeocodingService**

The `GeocodingService` is responsible for converting city names into geographical coordinates using the OpenWeatherMap
API.

#### **OpenWeatherMapService**

The `OpenWeatherMapService` fetches weather data from the OpenWeatherMap API using a city's coordinates.

---

## Event-Driven Architecture

The system uses an event-driven approach to handle notifications, with a `NotificationCreated` event being dispatched
whenever a notification is sent. This ensures that different parts of the system can respond to notification-related
events asynchronously.

---

## Design Patterns

Several design patterns are implemented to ensure flexibility and extensibility:

- **Strategy Pattern**: Used for different notification strategies based on weather conditions.
- **Factory Pattern**: Facilitates the creation of `User` entities.
- **Repository Pattern**: Abstracts data persistence in the `EloquentUserRepository`.
- **Value Object Pattern**: Implements immutability in the `Email` value object.

---

## Prerequisites

- PHP 8.x
- Laravel 11.x
- OpenWeatherMap API Key

---

## Installation

1. Install dependencies:
   ```bash
   composer install
   npm install
   php artisan key:generate
   ```

2. Set up environment variables in `.env` and/or in `.env.testing`:
   ```bash
   OPENWEATHERMAP_ONE_CALL_KEY=your-api-key
   OPENWEATHERMAP_GEOCODING_KEY=your-api-key
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. Run the Application. Optionally, you can use Docker via Laravel Sail:

    ```bash
    ./vendor/bin/sail up
    ```

---

## Testing

Unit tests are available for key components like `Notification`, `User`, and `WeatherData` entities. To run the tests:

```bash
./vendor/bin/sail artisan test
```

Or, without Docker:

```bash
php artisan test
```

