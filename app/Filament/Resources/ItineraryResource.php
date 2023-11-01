<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItineraryResource\Pages;
use App\Filament\Resources\ItineraryResource\RelationManagers;
use App\Models\Accomodation;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Schedule;
use App\Models\Transportation;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
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

                                  return $set('transporation_rate', $entity->rate);
                              }),
                        TextInput::make('transporation_rate')
                                 ->prefix('Rp')
                                 ->numeric(),
                        TextInput::make('distance')
                                 ->numeric()
                                 ->suffix('Km'),
                    ]),
                Repeater::make('schedules')
                        ->collapsible()
                        ->relationship()
                        ->orderColumn()
                        ->defaultItems(0)
                        ->columns(3)
                        ->columnSpan(2)
                        ->collapsed(true)
                        ->itemLabel(function ($state) {
                            $destination = Destination::find($state['destination_id']);

                            if (! $destination) {
                                return null;
                            }
                            $title = optional($destination)->dropdown_name;
                            $tod = $state['time_of_day'];
                            $pricePerPax = optional($destination)->price_per_pax;
                            $pax = $state['pax'];
                            $notes = $state['notes'];
                            $price = number_format($pricePerPax, 0, ',', '.').' x '.$pax;
                            $total = number_format($pricePerPax * $pax, 0, ',', '.');

                            if ($pricePerPax > 0 && $pax > 0) {
                                $title = $title.' (Rp'.$price.' = Rp'.$total.')';
                            }

                            if (isset(Schedule::TIME_OF_DAY[$tod])) {
                                $title = Schedule::TIME_OF_DAY[$tod].' - '.$title;
                            }

                            if ($notes) {
                                $title = $title.' | '.$notes;
                            }

                            return $title;
                        })
                        ->schema([
                            Select::make('destination_id')
                                  ->label('Destination')
                                  ->searchable()
                                  ->preload()
                                  ->live()
                                  ->relationship('destination')
                                  ->getOptionLabelFromRecordUsing(function (Destination $record) {
                                      return $record->dropdown_name;
                                  })
                                  ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                      $entity = Destination::find($state);
                                      $pricePerPax = optional($entity)->price_per_pax;
                                      $pax = $get('pax');
                                      $totalPrice = optional($entity)->price_per_pax * $pax;

                                      $set('price_per_pax', number_format($pricePerPax, 0, ',', '.'));
                                      $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                  })
                                  ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                      $entity = Destination::find($state);
                                      $pricePerPax = $entity->price_per_pax;
                                      $pax = $get('pax');
                                      $totalPrice = $entity->price_per_pax * $pax;

                                      $set('price_per_pax', number_format($pricePerPax, 0, ',', '.'));
                                      $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                  })
                                  ->createOptionForm([
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
                                  ]),
                            Select::make('time_of_day')
                                  ->options(Schedule::TIME_OF_DAY),
                            TextInput::make('notes'),
                            TextInput::make('price_per_pax')
                                     ->prefix('Rp')
                                     ->disabled(),
                            TextInput::make('pax')
                                     ->numeric()
                                     ->live()
                                     ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                         $pricePerPax = str_replace('.', '', $get('price_per_pax'));
                                         $pax = $state;
                                         $totalPrice = $pricePerPax * $pax;

                                         $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                     }),
                            TextInput::make('total_price')
                                     ->prefix('Rp')
                                     ->disabled(),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
