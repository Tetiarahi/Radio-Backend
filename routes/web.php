<?php

use App\Http\Controllers\AdminWeb\AuthController;
use App\Http\Controllers\AdminWeb\DashboardController;
use App\Http\Controllers\AdminWeb\NotificationController;
use App\Http\Controllers\AdminWeb\SettingController;
use App\Http\Controllers\AdminWeb\StationController;
use App\Http\Controllers\AdminWeb\StreamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/privacy-policy', 'privacy')->name('privacy');

// ── Admin Authentication Routes ───────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // ── Protected Admin Dashboard Routes ──────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Stations
        Route::resource('stations', StationController::class)->names('admin.stations');

        // Streams
        Route::resource('streams', StreamController::class)->names('admin.streams');

        // Notifications
        Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store', 'destroy'])->names('admin.notifications');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    });
});
