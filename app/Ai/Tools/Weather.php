<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class Weather implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Get the weather right now and today\'s forecast for a city.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        // Open-Meteo is free and needs no key: first find the city, then its weather.
        $place = Http::get('https://geocoding-api.open-meteo.com/v1/search', [
            'name' => $request['city'],
            'count' => 1,
        ])->json('results.0');

        if (! $place) {
            return "There is no city called {$request['city']}.";
        }

        $weather = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $place['latitude'],
            'longitude' => $place['longitude'],
            'current' => 'temperature_2m,precipitation,wind_speed_10m',
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max',
            'forecast_days' => 1,
            'timezone' => 'auto',
        ])->json();

        return json_encode([
            'place' => "{$place['name']}, {$place['country']}",
            'now' => $weather['current'],
            'today' => $weather['daily'],
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'city' => $schema->string()->description('A city name, for example Arusha.')->required(),
        ];
    }
}
