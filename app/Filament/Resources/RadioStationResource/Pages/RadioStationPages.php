<?php

namespace App\Filament\Resources\RadioStationResource\Pages;

use App\Filament\Resources\RadioStationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;

class ListRadioStations extends ListRecords
{
    protected static string $resource = RadioStationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

class CreateRadioStation extends CreateRecord
{
    protected static string $resource = RadioStationResource::class;
}

class EditRadioStation extends EditRecord
{
    protected static string $resource = RadioStationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
