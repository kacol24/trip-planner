<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Pages;
use App\Filament\Resources\TripResource\RelationManagers;
use App\Models\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                                           ->native(false)
                                           ->required()
                                           ->unique('trips', 'date')
                                           ->displayFormat('Y-m-d'),
                Forms\Components\TextInput::make('theme'),
                Forms\Components\RichEditor::make('description')
                                           ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('theme'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->groups([
                Tables\Grouping\Group::make('date')
                                     ->titlePrefixedWithLabel(false)
                                     ->getTitleFromRecordUsing(
                                         fn(Trip $record): string => strtoupper($record->date->format('d D'))
                                     )
                                     ->collapsible(),
            ])
            ->defaultGroup('date');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit'   => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
