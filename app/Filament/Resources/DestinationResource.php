<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;
use App\Models\Destination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->columnSpan(2),
                Forms\Components\Select::make('area_id')
                                       ->relationship('area', 'name')
                                       ->searchable()
                                       ->preload()
                                       ->required(),
                Forms\Components\Select::make('destination_type_id')
                                       ->relationship('destinationType', 'name')
                                       ->searchable()
                                       ->preload()
                                       ->required(),
                Forms\Components\RichEditor::make('notes')
                                           ->columnSpan(2),
                Forms\Components\TextInput::make('price_per_pax')
                                          ->prefix('Rp')
                                          ->numeric(),
                Forms\Components\TextInput::make('pax')
                                          ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                //Tables\Columns\TextColumn::make('area.name'),
                Tables\Columns\TextColumn::make('destinationType.name'),
                Tables\Columns\TextColumn::make('price_per_pax')
                                         ->prefix('Rp')
                                         ->numeric(),
                Tables\Columns\TextColumn::make('pax')
                                         ->numeric(),
                Tables\Columns\TextColumn::make('total_price')
                                         ->prefix('Rp')
                                         ->numeric(),
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
            ])
            ->groups([
                Tables\Grouping\Group::make('area.name')
                                     ->collapsible(),
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
            'index'  => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit'   => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
