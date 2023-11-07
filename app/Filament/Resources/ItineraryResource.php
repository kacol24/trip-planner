<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItineraryResource\Pages;
use App\Filament\Resources\ItineraryResource\RelationManagers;
use App\Filament\Resources\ItineraryResource\Widgets\BudgetTotalStats;
use App\Filament\Resources\ItineraryResource\Widgets\ItineraryBudgetOverview;
use App\Models\Accomodation;
use App\Models\Itinerary;
use App\Models\Transportation;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Itinerary';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                                           ->unique('itineraries', 'date', ignoreRecord: true)
                                           ->required()
                                           ->columnSpan([
                                               'default' => 2,
                                               'sm'      => 1,
                                           ])
                                           ->native(false),
                TextInput::make('theme')
                         ->columnSpan([
                             'default' => 2,
                             'sm'      => 1,
                         ]),
                Forms\Components\RichEditor::make('notes')
                                           ->columnSpan(2),
                Grid::make([
                    'default' => 2,
                    'sm'      => 3,
                ])
                    ->columnSpan(2)
                    ->schema([
                        Select::make('accomodation_id')
                              ->relationship('accomodation', 'name')
                              ->searchable()
                              ->preload()
                              ->live()
                              ->columnSpan([
                                  'default' => 2,
                                  'sm'      => 1,
                              ])
                              ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                  $accomodation = Accomodation::find($state);

                                  return $set('room_rate', optional($accomodation)->rate);
                              }),
                        TextInput::make('room_rate')
                                 ->prefix('Rp')
                                 ->columnSpan(1)
                                 ->numeric(),
                        TextInput::make('room_count')
                                 ->columnSpan(1)
                                 ->numeric(),
                    ]),
                Grid::make([
                    'default' => 2,
                    'sm'      => 3,
                ])
                    ->columnSpan(2)
                    ->schema([
                        Select::make('transportation_id')
                              ->relationship('transportation', 'name')
                              ->live()
                              ->columnSpan([
                                  'default' => 2,
                                  'sm'      => 1,
                              ])
                              ->native(false)
                              ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                  $entity = Transportation::find($state);

                                  return $set('transportation_rate', optional($entity)->rate);
                              }),
                        TextInput::make('transportation_rate')
                                 ->prefix('Rp')
                                 ->numeric(),
                        TextInput::make('distance')
                                 ->numeric()
                                 ->suffix('Km'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('date')
                                         ->formatStateUsing(function ($state) {
                                             return strtoupper(Carbon::parse($state)->format('d D'));
                                         }),
                Tables\Columns\TextColumn::make('theme'),
                Tables\Columns\TextColumn::make('accomodation_column')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->label('Accomodation')
                                         ->html(),
                Tables\Columns\TextColumn::make('transportation_column')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->label('Transportation')
                                         ->html(),
                Tables\Columns\TextColumn::make('wisata_cost')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->numeric(0, ',', '.')
                                         ->prefix('Rp'),
                Tables\Columns\TextColumn::make('kuliner_cost')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->numeric(0, ',', '.')
                                         ->prefix('Rp'),
                Tables\Columns\TextColumn::make('total_for_the_day')
                                         ->toggleable()
                                         ->numeric(0, ',', '.')
                                         ->prefix('Rp'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                                         ->iconButton(),
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
            RelationManagers\SchedulesRelationManager::class,
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
