<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;
use App\Models\Destination;
use App\Models\DestinationType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->required(),
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
                Forms\Components\TextInput::make('price_per_pax')
                                          ->prefix('Rp')
                                          ->numeric(),
                Forms\Components\RichEditor::make('notes')
                                           ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                //Tables\Columns\TextColumn::make('area.name'),
                Tables\Columns\SelectColumn::make('destination_type_id')
                                           ->label('Type')
                                           ->options(DestinationType::get()->pluck('name', 'id')),
                Tables\Columns\TextColumn::make('price_per_pax')
                                         ->label('Price/pax')
                                         ->prefix('Rp')
                                         ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('area_id')
                                           ->label('Area')
                                           ->relationship('area', 'name')
                                           ->searchable()
                                           ->preload()
                                           ->multiple(),
                Tables\Filters\SelectFilter::make('destination_type_id')
                                           ->label('Type')
                                           ->relationship('destinationType', 'name')
                                           ->searchable()
                                           ->preload()
                                           ->multiple(),
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
                                     ->collapsible()
                                     ->titlePrefixedWithLabel(false),
                Tables\Grouping\Group::make('destinationType.name')
                                     ->collapsible()
                                     ->titlePrefixedWithLabel(false),
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
