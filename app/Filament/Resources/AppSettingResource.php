<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppSettingResource\Pages;
use App\Models\AppSetting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AppSettingResource extends Resource
{
    protected static ?string $model = AppSetting::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationLabel(): string
    {
        return 'App Settings';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Configuration';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true)->disabled(fn ($operation) => $operation === 'edit'),
            Forms\Components\TextInput::make('label')->maxLength(100),
            Forms\Components\Select::make('group')
                ->options(['general' => 'General', 'appearance' => 'Appearance', 'social' => 'Social', 'contact' => 'Contact', 'other' => 'Other'])
                ->default('general')->required(),
            Forms\Components\Select::make('type')
                ->options(['string' => 'String', 'boolean' => 'Boolean', 'integer' => 'Integer', 'json' => 'JSON', 'color' => 'Color', 'url' => 'URL'])
                ->default('string')->required(),
            Forms\Components\Textarea::make('value')->rows(3),
            Forms\Components\Textarea::make('description')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')->badge()->sortable(),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('value')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('type')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(['general' => 'General', 'appearance' => 'Appearance', 'social' => 'Social', 'contact' => 'Contact']),
            ])
            ->actions([\Filament\Actions\EditAction::make()])
            ->defaultSort('group');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAppSettings::route('/'),
            'create' => Pages\CreateAppSetting::route('/create'),
            'edit'   => Pages\EditAppSetting::route('/{record}/edit'),
        ];
    }
}
