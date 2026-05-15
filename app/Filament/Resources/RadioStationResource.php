<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RadioStationResource\Pages;
use App\Models\RadioStation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RadioStationResource extends Resource
{
    protected static ?string $model = RadioStation::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-radio';
    }

    public static function getNavigationLabel(): string
    {
        return 'Radio Stations';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Broadcasting';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Station Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('slug', \Illuminate\Support\Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),

                    Forms\Components\TextInput::make('tagline')
                        ->maxLength(200)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('frequency')
                        ->placeholder('101.5 FM')
                        ->maxLength(20),

                    Forms\Components\Select::make('band')
                        ->options(['FM' => 'FM', 'AM' => 'AM', 'ONLINE' => 'Online'])
                        ->required()
                        ->default('FM'),

                    Forms\Components\TextInput::make('genre')->maxLength(50),
                    Forms\Components\TextInput::make('language')->maxLength(50)->default('English'),
                    Forms\Components\TextInput::make('country')->maxLength(50),
                    Forms\Components\TextInput::make('timezone')->maxLength(50),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ]),

            \Filament\Schemas\Components\Section::make('Station Logo')
                ->schema([
                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->directory('stations/logos')
                        ->disk('public')
                        ->maxSize(5120)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                        ->imageEditor(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->disk('public')
                    ->label('Logo')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('band')->badge(),
                Tables\Columns\TextColumn::make('frequency'),
                Tables\Columns\TextColumn::make('genre'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('streams_count')
                    ->counts('streams')
                    ->label('Streams'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('band')
                    ->options(['FM' => 'FM', 'AM' => 'AM', 'ONLINE' => 'Online']),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            RadioStationResource\RelationManagers\StreamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRadioStations::route('/'),
            'create' => Pages\CreateRadioStation::route('/create'),
            'edit'   => Pages\EditRadioStation::route('/{record}/edit'),
        ];
    }
}
