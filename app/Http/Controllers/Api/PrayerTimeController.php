<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * PrayerTimeController — fetches real-time prayer times from Aladhan API.
 */
class PrayerTimeController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input('city', 'Dhaka');
        $country = $request->input('country', 'Bangladesh');

        // Cache the prayer times for 24 hours based on city and date to save API calls
        $cacheKey = "prayer_times_" . strtolower($city) . "_" . strtolower($country) . "_" . now()->toDateString();

        $prayerTimeData = Cache::remember($cacheKey, now()->addHours(24), function () use ($city, $country) {
            $response = Http::timeout(10)->get('http://api.aladhan.com/v1/timingsByCity', [
                'city' => $city,
                'country' => $country,
                'method' => 1 // 1: University of Islamic Sciences, Karachi (Common in South Asia)
            ]);

            if ($response->successful() && isset($response['data']['timings'])) {
                $timings = $response['data']['timings'];
                
                // Format the Aladhan response to match our frontend UI structure
                return [
                    'city' => ucfirst($city),
                    'date' => now()->format('Y-m-d'),
                    'prayers' => [
                        'Fajr' => $timings['Fajr'],
                        'Sunrise' => $timings['Sunrise'],
                        'Dhuhr' => $timings['Dhuhr'],
                        'Asr' => $timings['Asr'],
                        'Maghrib' => $timings['Maghrib'],
                        'Isha' => $timings['Isha'],
                    ],
                ];
            }

            return null; // Triggers 404 block below if API fails
        });

        if (!$prayerTimeData) {
            return response()->json([
                'data' => null,
                'message' => 'Failed to fetch prayer times from Aladhan API.',
            ], 404);
        }

        return response()->json([
            'data' => $prayerTimeData
        ]);
    }
}

