<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\NowPlayingController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Radio Mobile App
|--------------------------------------------------------------------------
|
| All public routes are rate-limited to 60 req/min.
| All admin routes require Sanctum token authentication.
|
*/

Route::prefix('v1')->middleware(['api', 'throttle:60,1'])->group(function () {

    // ── Public Routes ─────────────────────────────────────────────────────────

    // Radio Stations
    Route::get('/stations', [StationController::class, 'index']);
    Route::get('/stations/{station}', [StationController::class, 'show']);

    // Live Metadata
    Route::get('/now-playing/{stream}', [NowPlayingController::class, 'show']);

    // App Settings
    Route::get('/settings', [SettingsController::class, 'index']);

    // Device Token Registration (push notifications)
    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
    Route::delete('/device-tokens/{token}', [DeviceTokenController::class, 'destroy']);

    // ── Admin Authentication ──────────────────────────────────────────────────

    Route::post('/admin/login', [AuthController::class, 'login']);

    // ── Protected Admin Routes ────────────────────────────────────────────────

    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Station Management
        Route::apiResource('stations', \App\Http\Controllers\Admin\AdminStationController::class);
        Route::post('stations/{station}/logo', [\App\Http\Controllers\Admin\AdminStationController::class, 'uploadLogo']);

        // Stream Management
        Route::apiResource('streams', \App\Http\Controllers\Admin\AdminStreamController::class);

        // Push Notifications
        Route::apiResource('notifications', \App\Http\Controllers\Admin\AdminNotificationController::class);
        Route::post('notifications/{notification}/send', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'send']);

        // App Settings
        Route::get('settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index']);
        Route::put('settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update']);

        // Analytics
        Route::get('analytics', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'index']);
    });
});
