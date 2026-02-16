<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Illuminate\Http\Request;

/**
 * PrayerTimeController — serves prayer times from the database.
 *
 * The frontend expects:
 * {
 *   "data": {
 *     "city": "Dhaka",
 *     "date": "2025-02-15",
 *     "prayers": {
 *       "Fajr": "05:12",
 *       "Sunrise": "06:30",
 *       ...
 *     }
 *   }
 * }
 */
class PrayerTimeController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input('city', 'Dhaka');
        $date = $request->input('date', now()->toDateString());

        $prayerTime = PrayerTime::where('city', $city)
            ->where('date', $date)
            ->first();

        if (!$prayerTime) {
            // Fall back to latest available for the city
            $prayerTime = PrayerTime::where('city', $city)
                ->latest('date')
                ->first();
        }

        if (!$prayerTime) {
            return response()->json([
                'data' => null,
                'message' => 'No prayer times found. Please seed the database.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'city' => $prayerTime->city,
                'date' => $prayerTime->date->format('Y-m-d'),
                'prayers' => [
                    'Fajr' => $prayerTime->fajr,
                    'Sunrise' => $prayerTime->sunrise,
                    'Dhuhr' => $prayerTime->dhuhr,
                    'Asr' => $prayerTime->asr,
                    'Maghrib' => $prayerTime->maghrib,
                    'Isha' => $prayerTime->isha,
                ],
            ],
        ]);
    }
}
