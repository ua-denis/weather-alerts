@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>User Profile</h1>

        <h2>{{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="precipitation">Precipitation Threshold:</label>
                <input type="number" name="precipitation" id="precipitation"
                       value="{{ old('precipitation', $user->profile->notification_thresholds['precipitation'] ?? 10) }}">
            </div>
            <div class="form-group">
                <label for="uv_index">UV Index Threshold:</label>
                <input type="number" name="uv_index" id="uv_index"
                       value="{{ old('uv_index', $user->profile->notification_thresholds['uv_index'] ?? 6) }}">
            </div>

            <div id="city-inputs">
                <h3>Add Cities</h3>
                <button type="button" onclick="addCityInput()">Add City</button>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
@endsection
