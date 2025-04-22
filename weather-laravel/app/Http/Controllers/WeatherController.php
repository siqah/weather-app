<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $city = $request->query('city');

        if (!$city) {
            return response()->json(['error' => 'City is required'], 400);
        }

        // Get API key from .env
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

        try {
            $response = Http::get($apiUrl);

            if ($response->failed()) {
                return response()->json(['error' => 'Unable to fetch weather data'], $response->status());
            }

            $data = $response->json();

            return response()->json([
                'city' => $data['name'],
                'temperature' => $data['main']['temp'] . '°C',
                'description' => $data['weather'][0]['description'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching weather data'], 500);
        }
    }
}
