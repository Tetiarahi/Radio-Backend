<?php

namespace App\Filament\Resources\RadioStationResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class StreamsRelationManager extends RelationManager
{
    protected static string $relationship = 'streams';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('label')->required()->maxLength(100),
            Forms\Components\TextInput::make('stream_url')->required()->url(),
            Forms\Components\Select::make('stream_type')
                ->options(['icecast' => 'Icecast', 'shoutcast' => 'Shoutcast', 'hls' => 'HLS', 'other' => 'Other'])
                ->required()->default('icecast'),
            Forms\Components\TextInput::make('codec')->placeholder('mp3, aac, opus'),
            Forms\Components\TextInput::make('bitrate')->numeric()->suffix('kbps'),
            Forms\Components\TextInput::make('metadata_url')->url()->label('Metadata URL'),
            Forms\Components\Toggle::make('is_https')->default(true)->label('HTTPS'),
            Forms\Components\Toggle::make('is_default')->default(false)->label('Default Stream'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('stream_type'),
                Tables\Columns\TextColumn::make('bitrate')->suffix(' kbps'),
                Tables\Columns\IconColumn::make('is_https')->boolean()->label('HTTPS'),
                Tables\Columns\IconColumn::make('is_default')->boolean()->label('Default'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->headerActions([\Filament\Actions\CreateAction::make()])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }
}
