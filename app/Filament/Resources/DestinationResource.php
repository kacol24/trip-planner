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

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getSchema());
    }

    public static function getSchema()
    {
        return [
            Forms\Components\TextInput::make('name')
                                      ->columnSpan(2)
                                      ->required(),
            Forms\Components\Grid::make([
                'default' => 2,
                'sm'      => 3,
            ])
                                 ->columnSpan(2)
                                 ->schema([
                                     Forms\Components\Select::make('area_id')
                                                            ->columnSpan([
                                                                'default' => 2,
                                                                'sm'      => 1,
                                                            ])
                                                            ->relationship('area', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->required(),
                                     Forms\Components\Select::make('destination_type_id')
                                                            ->columnSpan([
                                                                'default' => 2,
                                                                'sm'      => 1,
                                                            ])
                                                            ->relationship('destinationType', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->required(),
                                     Forms\Components\TextInput::make('price_per_pax')
                                                               ->columnSpan([
                                                                   'default' => 2,
                                                                   'sm'      => 1,
                                                               ])
                                                               ->prefix('Rp')
                                                               ->numeric(),
                                 ]),
            Forms\Components\RichEditor::make('notes')
                                       ->columnSpan(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                         ->searchable()
                                         ->description(function (Destination $record) {
                                             return $record->destinationType->name;
                                         }),
                Tables\Columns\TextColumn::make('area.name'),
                Tables\Columns\TextColumn::make('price_per_pax')
                                         ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Actions\EditAction::make()
                                         ->slideOver(),
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
            //'edit'   => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
