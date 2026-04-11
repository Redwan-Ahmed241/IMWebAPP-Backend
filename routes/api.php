<?php

/**
 * API Routes
 *
 * Architecture note:
 * - Public routes: products, articles, prayer times, daily-ayah, blood-donations
 * - Protected routes: auth/logout (requires Sanctum token)
 * - Auth routes: login + register (public)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\PrayerTimeController;
use App\Http\Controllers\Api\DailyAyahController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BloodDonationController;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user',    [AuthController::class, 'user']);
    });
});

/*
|--------------------------------------------------------------------------
| Public Resources
|--------------------------------------------------------------------------
*/
Route::apiResource('products',   ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('articles',   ArticleController::class)->only(['index', 'show']);
Route::post('/orders', [OrderController::class, 'store']);

Route::get('/prayer-times', [PrayerTimeController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Daily Ayah (Stub — TODO)
|--------------------------------------------------------------------------
| This endpoint is intentionally stubbed. See DailyAyahController for
| implementation guidance.
*/
Route::get('/daily-ayah', [DailyAyahController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Blood Donation
|--------------------------------------------------------------------------
*/
Route::post('/blood-donations', [BloodDonationController::class, 'store']);
Route::get('/blood-donations', [BloodDonationController::class, 'index']);

