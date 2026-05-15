<?php

namespace App\Filament\Resources\AppSettingResource\Pages;

use App\Filament\Resources\AppSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;

class ListAppSettings extends ListRecords
{
    protected static string $resource = AppSettingResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}

class CreateAppSetting extends CreateRecord
{
    protected static string $resource = AppSettingResource::class;
}

class EditAppSetting extends EditRecord
{
    protected static string $resource = AppSettingResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
