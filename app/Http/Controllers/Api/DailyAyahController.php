<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * DailyAyahController — fetches motivational verses from Alquran.cloud API
 */
class DailyAyahController extends Controller
{
    // A curated list of highly motivational and comforting Ayahs (Surah:Ayah)
    private $ayahs = [
        '2:286', // Allah does not burden a soul...
        '94:5',  // With hardship comes ease
        '3:139', // Do not lose hope, nor be sad
        '2:152', // Remember Me, I will remember you
        '40:60', // Call upon Me, I will respond to you
        '2:216', // Perhaps you dislike a thing and Allah makes therein much good
        '8:30',  // Allah is the best of planners
        '55:13', // Which of the favors of your Lord will you deny?
        '13:28', // Verily, in the remembrance of Allah do hearts find rest
        '39:53', // Do not despair of the mercy of Allah
        '50:16', // We are closer to him than his jugular vein
        '65:3',  // Whoever relies upon Allah, He will be sufficient for him
    ];

    public function index()
    {
        // Pick an Ayah based on the day of the year so it changes daily
        $dayOfYear = now()->dayOfYear;
        $ayahReference = $this->ayahs[$dayOfYear % count($this->ayahs)];
        $cacheKey = "daily_ayah_" . $ayahReference;

        $ayahData = Cache::remember($cacheKey, now()->addHours(24), function () use ($ayahReference) {
            $response = Http::timeout(10)->get("http://api.alquran.cloud/v1/ayah/{$ayahReference}/editions/quran-uthmani,en.sahih");

            if ($response->successful()) {
                $arabicData = $response['data'][0];
                $englishData = $response['data'][1];
                
                return [
                    'arabic' => $arabicData['text'],
                    'translation' => $englishData['text'],
                    'reference' => "Surah {$arabicData['surah']['englishName']} ({$ayahReference})",
                    'dua' => 'O Allah, make the Quran the spring of my heart, the light of my chest, the banisher of my sadness, and the reliever of my distress.',
                ];
            }
            return null;
        });

        if (!$ayahData) {
            return response()->json([
                'data' => [
                    'arabic' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                    'translation' => 'In the name of Allah, the Most Gracious, the Most Merciful.',
                    'reference' => 'Surah Al-Fatiha (1:1)',
                    'dua' => 'O Allah, open for me the doors of Your mercy.',
                ]
            ]);
        }

        return response()->json([
            'data' => $ayahData
        ]);
    }
}
