<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Pool;

/**
 * PrayerTimeController — fetches real-time prayer times from Aladhan API.
 * Added logic for both Shafi and Hanafi Asr times.
 */
class PrayerTimeController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input('city', 'Dhaka');
        $country = $request->input('country', 'Bangladesh');

        // Cache the prayer times for 24 hours based on city and date to save API calls
        $cacheKey = "prayer_times_v2_" . strtolower($city) . "_" . strtolower($country) . "_" . now()->toDateString();

        $prayerTimeData = Cache::remember($cacheKey, now()->addHours(24), function () use ($city, $country) {
            // Fetch both Shafi (school 0) and Hanafi (school 1) simultaneously
            $responses = Http::pool(fn (Pool $pool) => [
                $pool->as('shafi')->timeout(10)->get('http://api.aladhan.com/v1/timingsByCity', [
                    'city' => $city, 'country' => $country, 'method' => 1, 'school' => 0
                ]),
                $pool->as('hanafi')->timeout(10)->get('http://api.aladhan.com/v1/timingsByCity', [
                    'city' => $city, 'country' => $country, 'method' => 1, 'school' => 1
                ])
            ]);

            if ($responses['shafi']->successful() && $responses['hanafi']->successful()) {
                $shafiTimings = $responses['shafi']['data']['timings'];
                $hanafiTimings = $responses['hanafi']['data']['timings'];
                
                return [
                    'city' => ucfirst($city),
                    'date' => now()->format('Y-m-d'),
                    'prayers' => [
                        'Fajr' => $shafiTimings['Fajr'],
                        'Sunrise' => $shafiTimings['Sunrise'],
                        'Dhuhr' => $shafiTimings['Dhuhr'],
                        'Asr (Shafi)' => $shafiTimings['Asr'],
                        'Asr (Hanafi)' => $hanafiTimings['Asr'],
                        'Maghrib' => $shafiTimings['Maghrib'],
                        'Isha' => $shafiTimings['Isha'],
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

