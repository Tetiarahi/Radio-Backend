<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PushNotificationResource\Pages;
use App\Models\PushNotification;
use App\Services\PushNotificationService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PushNotificationResource extends Resource
{
    protected static ?string $model = PushNotification::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-bell';
    }

    public static function getNavigationLabel(): string
    {
        return 'Push Notifications';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Communications';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Notification Content')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()->maxLength(100),

                    Forms\Components\Textarea::make('body')
                        ->required()->maxLength(500)->rows(3),

                    Forms\Components\TextInput::make('image_url')
                        ->url()->label('Image URL'),

                    Forms\Components\Select::make('target_audience')
                        ->options(['all' => 'All Devices', 'android' => 'Android Only', 'ios' => 'iOS Only'])
                        ->default('all')->required(),

                    Forms\Components\KeyValue::make('data')
                        ->label('Extra Data (JSON for deep linking)'),

                    Forms\Components\DateTimePicker::make('scheduled_at')
                        ->label('Schedule For (leave empty for draft)')
                        ->after('now'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('body')->limit(60),
                Tables\Columns\TextColumn::make('target_audience')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('recipients_count')->label('Sent To'),
                Tables\Columns\TextColumn::make('scheduled_at')->since()->label('Scheduled'),
                Tables\Columns\TextColumn::make('sent_at')->since()->label('Sent'),
                Tables\Columns\TextColumn::make('creator.name')->label('By'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['draft' => 'Draft', 'scheduled' => 'Scheduled', 'sent' => 'Sent', 'failed' => 'Failed']),
            ])
            ->actions([
                \Filament\Actions\Action::make('send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send Notification Now?')
                    ->modalDescription('This will send the notification immediately to all targeted devices.')
                    ->visible(fn ($record) => $record->status !== 'sent')
                    ->action(function (PushNotification $record) {
                        app(PushNotificationService::class)->send($record);
                        Notification::make()->success()->title("Sent to {$record->recipients_count} devices.")->send();
                    }),

                \Filament\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status !== 'sent'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPushNotifications::route('/'),
            'create' => Pages\CreatePushNotification::route('/create'),
            'edit'   => Pages\EditPushNotification::route('/{record}/edit'),
        ];
    }
}
