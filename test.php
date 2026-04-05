<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test_random_'.rand(100,999).'@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password')
    ]);
    echo "USER CREATED. ID: " . $user->id . "\n";
    $token = $user->createToken('auth-token')->plainTextToken;
    echo "TOKEN CREATED: " . $token . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
