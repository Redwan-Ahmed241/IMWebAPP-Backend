<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->date('date');
            $table->string('fajr');      // e.g. "05:12"
            $table->string('sunrise');   // e.g. "06:30"
            $table->string('dhuhr');     // e.g. "12:05"
            $table->string('asr');       // e.g. "15:30"
            $table->string('maghrib');   // e.g. "18:15"
            $table->string('isha');      // e.g. "19:40"
            $table->timestamps();

            $table->unique(['city', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};
