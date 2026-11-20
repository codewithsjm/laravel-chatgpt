<?php

use App\Ai\Agents\Assistant;
use App\Ai\Tools\Weather;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Tools\Request;

test('the weather tool finds the city, then its weather', function () {
    // No real requests in tests: Laravel's HTTP fake answers instead.
    Http::fake([
        'geocoding-api.open-meteo.com/*' => Http::response(['results' => [
            ['name' => 'Arusha', 'country' => 'Tanzania', 'latitude' => -3.37, 'longitude' => 36.68],
        ]]),
        'api.open-meteo.com/*' => Http::response([
            'current' => ['temperature_2m' => 24.5, 'precipitation' => 0],
            'daily' => ['precipitation_probability_max' => [70]],
        ]),
    ]);

    $result = json_decode((new Weather)->handle(new Request(['city' => 'Arusha'])), true);

    expect($result['place'])->toBe('Arusha, Tanzania')
        ->and($result['now']['temperature_2m'])->toBe(24.5)
        ->and($result['today']['precipitation_probability_max'])->toBe([70]);
});

test('an unknown city gets a clear answer', function () {
    Http::fake(['geocoding-api.open-meteo.com/*' => Http::response([])]);

    expect((new Weather)->handle(new Request(['city' => 'Atlantis'])))
        ->toBe('There is no city called Atlantis.');
});

test('the assistant has the weather tool', function () {
    expect((new Assistant)->tools())->sequence(fn ($tool) => $tool->toBeInstanceOf(Weather::class));
});
