<?php

/**
 * Vercel Serverless Function Entry Point for Laravel.
 *
 * This file boots Laravel in a serverless context, ensuring:
 * 1. The correct base path is set
 * 2. Storage directories exist in /tmp (writable on Vercel)
 * 3. The Laravel app handles the incoming request
 */

// Set the base path to the project root (one level up from /api)
define('LARAVEL_BASE_PATH', dirname(__DIR__));

// Ensure /tmp storage directories exist
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Register the Composer autoloader
require LARAVEL_BASE_PATH . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once LARAVEL_BASE_PATH . '/bootstrap/app.php';

// Override storage path to writable /tmp
$app->useStoragePath('/tmp/storage');

// Handle the incoming request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
