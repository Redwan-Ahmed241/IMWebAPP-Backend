<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Article;
use App\Models\PrayerTime;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Demo User ──
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@idealmuslimshop.com',
            'password' => Hash::make('password'),
        ]);

        // ── Categories ──
        $categories = collect([
            ['name' => 'Books', 'description' => 'Islamic literature, tafsir, and scholarly works'],
            ['name' => 'Prayer Essentials', 'description' => 'Prayer mats, caps, and accessories'],
            ['name' => 'Modest Fashion', 'description' => 'Elegant and modest clothing for all occasions'],
            ['name' => 'Home & Décor', 'description' => 'Islamic calligraphy, wall art, and home accents'],
            ['name' => 'Fragrances', 'description' => 'Attar, oud, and natural fragrances'],
        ])->map(function ($cat) {
            return Category::create(array_merge($cat, [
                'slug' => Str::slug($cat['name']),
            ]));
        });

        // ── Products ──
        $products = [
            ['category' => 'Books', 'name' => 'The Sealed Nectar (Ar-Raheeq Al-Makhtum)', 'description' => 'Award-winning biography of Prophet Muhammad ﷺ by Safiur-Rahman Mubarakpuri.', 'price' => 18.99, 'is_featured' => true],
            ['category' => 'Books', 'name' => 'Fortress of the Muslim (Hisnul Muslim)', 'description' => 'Collection of authentic duas and supplications for daily life.', 'price' => 8.50, 'is_featured' => true],
            ['category' => 'Books', 'name' => 'Tafsir Ibn Kathir (Abridged)', 'description' => 'Comprehensive Quran commentary in 10 volumes.', 'price' => 89.99, 'is_featured' => false],
            ['category' => 'Books', 'name' => 'Riyad as-Salihin', 'description' => 'Gardens of the Righteous — collection of hadith by Imam An-Nawawi.', 'price' => 22.00, 'is_featured' => false],
            ['category' => 'Prayer Essentials', 'name' => 'Premium Turkish Prayer Mat', 'description' => 'Hand-woven high-quality prayer mat with soft velvet finish.', 'price' => 34.99, 'is_featured' => true],
            ['category' => 'Prayer Essentials', 'name' => 'Digital Tasbeeh Counter', 'description' => 'Electronic counter for dhikr with LED display and silent click.', 'price' => 12.99, 'is_featured' => true],
            ['category' => 'Prayer Essentials', 'name' => 'Kufi Prayer Cap (White)', 'description' => 'Comfortable cotton kufi cap for daily prayers.', 'price' => 9.99, 'is_featured' => false],
            ['category' => 'Modest Fashion', 'name' => 'Premium Abaya — Midnight Black', 'description' => 'Luxurious nida fabric abaya with elegant embroidery detailing.', 'price' => 65.00, 'is_featured' => true],
            ['category' => 'Modest Fashion', 'name' => 'Linen Thobe — Classic White', 'description' => 'Breathable linen thobe perfect for summer and Jummah prayers.', 'price' => 45.00, 'is_featured' => true],
            ['category' => 'Home & Décor', 'name' => 'Ayatul Kursi — Gold Calligraphy Frame', 'description' => 'Stunning metal calligraphy of Ayatul Kursi for your living room.', 'price' => 79.99, 'is_featured' => true],
            ['category' => 'Home & Décor', 'name' => 'Ceramic Incense Burner — Islamic Star', 'description' => 'Handmade ceramic burner with geometric Islamic star pattern.', 'price' => 24.50, 'is_featured' => false],
            ['category' => 'Fragrances', 'name' => 'Original Oud — Arabian Nights', 'description' => 'Premium oud oil sourced from Assam, aged for 20 years.', 'price' => 120.00, 'is_featured' => true],
        ];

        foreach ($products as $p) {
            $category = $categories->firstWhere('name', $p['category']);
            Product::create([
                'category_id' => $category->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price'],
                'is_featured' => $p['is_featured'],
                'stock' => rand(5, 100),
            ]);
        }

        // ── Articles ──
        $articles = [
            [
                'title' => 'The Virtues of Dhikr in Daily Life',
                'author' => 'Sheikh Ahmad Al-Nouri',
                'excerpt' => 'Discover how the remembrance of Allah transforms your daily routine and brings inner peace.',
                'body' => '<h2>What is Dhikr?</h2><p>Dhikr, the remembrance of Allah, is one of the most powerful spiritual practices in Islam. The Prophet ﷺ said: <em>"The example of the one who remembers his Lord and the one who does not remember Him is like the example of the living and the dead."</em> (Bukhari)</p><h2>Benefits of Regular Dhikr</h2><p>Regular dhikr brings numerous benefits: tranquility of the heart, protection from Shaytan, earning of rewards, and closeness to Allah. The Quran states: <em>"Verily, in the remembrance of Allah do hearts find rest."</em> (13:28)</p><h2>Daily Dhikr Routine</h2><p>Start with morning and evening adhkar, then incorporate dhikr after every salah. SubhanAllah, Alhamdulillah, and Allahu Akbar — 33 times each — is a sunnah practice that takes only a few minutes but carries immense reward.</p>',
            ],
            [
                'title' => 'Understanding Zakat: A Complete Guide',
                'author' => 'Dr. Fatima Zahra',
                'excerpt' => 'Everything you need to know about the third pillar of Islam — from calculation to distribution.',
                'body' => '<h2>The Obligation of Zakat</h2><p>Zakat is the third pillar of Islam and is obligatory upon every Muslim who meets the nisab threshold. It serves as a means of wealth purification and social welfare.</p><h2>Calculating Your Zakat</h2><p>Zakat is 2.5% of your total qualifying wealth held over one lunar year. This includes cash savings, gold, silver, investments, and business inventory.</p><h2>Who Receives Zakat?</h2><p>The Quran identifies eight categories of zakat recipients in Surah At-Tawbah (9:60): the poor, the needy, zakat administrators, those whose hearts are to be reconciled, those in bondage, the debt-ridden, in the cause of Allah, and the wayfarer.</p>',
            ],
            [
                'title' => '5 Habits of Productive Muslims',
                'author' => 'Ustadh Ibrahim Malik',
                'excerpt' => 'Practical tips from the Sunnah to boost your productivity and time management.',
                'body' => '<h2>1. Wake Up Before Fajr</h2><p>The Prophet ﷺ prayed for barakah in the morning hours. Waking up for Tahajjud and Fajr sets a productive tone for the entire day.</p><h2>2. Plan with Bismillah</h2><p>Begin every task with bismillah and sincere intention. This transforms mundane activities into acts of worship.</p><h2>3. Take Breaks with Salah</h2><p>The five daily prayers naturally structure your day into focused work blocks with mindful breaks.</p><h2>4. Practice Gratitude (Shukr)</h2><p>Keeping a gratitude journal inspired by the sunnah of saying Alhamdulillah improves mental well-being.</p><h2>5. End Your Day with Reflection</h2><p>Muhasabah — self-accounting — before sleep helps you grow and plan for tomorrow.</p>',
            ],
        ];

        foreach ($articles as $a) {
            Article::create(array_merge($a, [
                'slug' => Str::slug($a['title']),
                'published_at' => now()->subDays(rand(1, 30)),
            ]));
        }

        // ── Prayer Times (sample for Dhaka, today ± 3 days) ──
        $prayerData = [
            ['fajr' => '05:12', 'sunrise' => '06:28', 'dhuhr' => '12:05', 'asr' => '15:32', 'maghrib' => '17:42', 'isha' => '19:00'],
            ['fajr' => '05:11', 'sunrise' => '06:27', 'dhuhr' => '12:05', 'asr' => '15:33', 'maghrib' => '17:43', 'isha' => '19:01'],
            ['fajr' => '05:10', 'sunrise' => '06:26', 'dhuhr' => '12:05', 'asr' => '15:34', 'maghrib' => '17:44', 'isha' => '19:02'],
            ['fajr' => '05:09', 'sunrise' => '06:25', 'dhuhr' => '12:04', 'asr' => '15:35', 'maghrib' => '17:45', 'isha' => '19:03'],
            ['fajr' => '05:08', 'sunrise' => '06:24', 'dhuhr' => '12:04', 'asr' => '15:36', 'maghrib' => '17:46', 'isha' => '19:04'],
            ['fajr' => '05:07', 'sunrise' => '06:23', 'dhuhr' => '12:04', 'asr' => '15:37', 'maghrib' => '17:47', 'isha' => '19:05'],
            ['fajr' => '05:06', 'sunrise' => '06:22', 'dhuhr' => '12:03', 'asr' => '15:38', 'maghrib' => '17:48', 'isha' => '19:06'],
        ];

        foreach ($prayerData as $i => $times) {
            PrayerTime::create(array_merge($times, [
                'city' => 'Dhaka',
                'date' => now()->subDays(3)->addDays($i)->toDateString(),
            ]));
        }
    }
}
