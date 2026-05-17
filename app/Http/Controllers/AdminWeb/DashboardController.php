<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\PushNotification;
use App\Models\RadioStation;
use App\Models\StreamConfig;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard overview.
     */
    public function index(): View
    {
        $totalStations = RadioStation::count();
        $activeStations = RadioStation::where('is_active', true)->count();
        $activeStreams = StreamConfig::where('is_active', true)->count();
        $totalNotifications = PushNotification::count();

        $recentStations = RadioStation::with('defaultStream')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $recentNotifications = PushNotification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $appName = AppSetting::get('app_name', 'Radio App');

        return view('admin.dashboard', compact(
            'totalStations',
            'activeStations',
            'activeStreams',
            'totalNotifications',
            'recentStations',
            'recentNotifications',
            'appName'
        ));
    }
}
