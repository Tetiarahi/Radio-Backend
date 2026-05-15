<?php

namespace App\Filament\Resources\PushNotificationResource\Pages;

use App\Filament\Resources\PushNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;

class ListPushNotifications extends ListRecords
{
    protected static string $resource = PushNotificationResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}

class CreatePushNotification extends CreateRecord
{
    protected static string $resource = PushNotificationResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['created_by'] = auth()->id();
        $data['status'] = isset($data['scheduled_at']) ? 'scheduled' : 'draft';
        return $data;
    }
}

class EditPushNotification extends EditRecord
{
    protected static string $resource = PushNotificationResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
