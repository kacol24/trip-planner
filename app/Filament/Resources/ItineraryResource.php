<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItineraryResource\Pages;
use App\Filament\Resources\ItineraryResource\RelationManagers;
use App\Models\Accomodation;
use App\Models\Itinerary;
use App\Models\Transportation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                                           ->unique('itineraries', 'date')
                                           ->required()
                                           ->native(false),
                Forms\Components\TextInput::make('theme'),
                Forms\Components\RichEditor::make('notes')
                                           ->columnSpan(2),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('accomodation_id')
                                           ->relationship('accomodation', 'name')
                                           ->searchable()
                                           ->preload()
                                           ->live()
                                           ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                               $accomodation = Accomodation::find($state);

                                               return $set('room_rate', $accomodation->rate);
                                           }),
                    Forms\Components\TextInput::make('room_rate')
                                              ->numeric(),
                    Forms\Components\TextInput::make('room_count')
                                              ->numeric(),
                ]),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('transportation_id')
                                           ->relationship('transportation', 'name')
                                           ->live()
                                           ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                               $entity = Transportation::find($state);

                                               return $set('transporation_rate', $entity->rate);
                                           }),
                    Forms\Components\TextInput::make('transporation_rate')
                                              ->numeric(),
                    Forms\Components\TextInput::make('distance')
                                              ->numeric(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index'  => Pages\ListItineraries::route('/'),
            'create' => Pages\CreateItinerary::route('/create'),
            'edit'   => Pages\EditItinerary::route('/{record}/edit'),
        ];
    }
}
