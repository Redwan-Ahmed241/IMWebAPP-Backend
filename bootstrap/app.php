<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Apply CORS middleware to API routes first (before other middleware)
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Force JSON responses — never try to render HTML views (Vercel has no view engine)
        $exceptions->shouldRenderJsonWhen(fn () => true);
    })->create();

// Vercel read-only filesystem fix
if (isset($_SERVER['VERCEL']) || env('APP_ENV') === 'production') {
    $storage = '/tmp/storage';
    $app->useStoragePath($storage);
    
    // Create required directories inside /tmp
    $dirs = [
        "{$storage}/framework/views",
        "{$storage}/framework/cache/data",
        "{$storage}/framework/sessions",
        "{$storage}/logs",
        "{$storage}/bootstrap/cache"
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}

return $app;
