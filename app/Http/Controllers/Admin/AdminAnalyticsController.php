<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\PushNotification;
use App\Models\RadioStation;
use Illuminate\Http\JsonResponse;

class AdminAnalyticsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_stations'       => RadioStation::count(),
                'active_stations'      => RadioStation::where('is_active', true)->count(),
                'total_devices'        => DeviceToken::count(),
                'active_devices'       => DeviceToken::active()->count(),
                'android_devices'      => DeviceToken::active()->byPlatform('android')->count(),
                'ios_devices'          => DeviceToken::active()->byPlatform('ios')->count(),
                'total_notifications'  => PushNotification::count(),
                'sent_notifications'   => PushNotification::where('status', 'sent')->count(),
                'draft_notifications'  => PushNotification::where('status', 'draft')->count(),
            ],
        ]);
    }
}
