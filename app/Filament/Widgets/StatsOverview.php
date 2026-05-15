<?php

namespace App\Filament\Widgets;

use App\Models\DeviceToken;
use App\Models\PushNotification;
use App\Models\RadioStation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Active Stations', RadioStation::where('is_active', true)->count())
                ->description('Radio stations broadcasting')
                ->descriptionIcon('heroicon-m-radio')
                ->color('success'),

            Stat::make('Registered Devices', DeviceToken::active()->count())
                ->description(DeviceToken::active()->byPlatform('android')->count() . ' Android · ' . DeviceToken::active()->byPlatform('ios')->count() . ' iOS')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('primary'),

            Stat::make('Notifications Sent', PushNotification::where('status', 'sent')->count())
                ->description(PushNotification::where('status', 'draft')->count() . ' drafts pending')
                ->descriptionIcon('heroicon-m-bell')
                ->color('warning'),
        ];
    }
}
