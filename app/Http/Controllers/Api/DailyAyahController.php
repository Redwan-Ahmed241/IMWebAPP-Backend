<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * DailyAyahController — STUB ENDPOINT
 *
 * ⚠️ THIS IS INTENTIONALLY INCOMPLETE ⚠️
 *
 * The developer should implement one of these strategies:
 *
 * Strategy A — Database seeded:
 *   1. Create an `ayahs` migration (id, surah, ayah_number, arabic, translation, reference)
 *   2. Seed ~30 popular ayahs
 *   3. Pick one per day: Ayah::find(now()->dayOfYear % Ayah::count() + 1)
 *
 * Strategy B — External API:
 *   1. Call https://api.alquran.cloud/v1/ayah/{number}/editions/quran-uthmani,en.asad
 *   2. Cache the result for 24 hours
 *   3. Return cached data
 *
 * Strategy C — Static JSON:
 *   1. Create a JSON file in storage/app/ayahs.json
 *   2. Load and pick one per day of year
 *
 * ALSO:
 * - Create a similar approach for "Dua of the Day"
 * - Add caching (Cache::remember) for performance
 */
class DailyAyahController extends Controller
{
    public function index()
    {
        // TODO: Replace this hardcoded response with one of the strategies above.
        return response()->json([
            'data' => [
                'arabic' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'translation' => 'In the name of Allah, the Most Gracious, the Most Merciful.',
                'reference' => 'Surah Al-Fatiha (1:1)',
                'dua' => 'O Allah, open for me the doors of Your mercy.',
            ],
        ]);
    }
}
