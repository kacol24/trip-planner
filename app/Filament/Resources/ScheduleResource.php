<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Itinerary';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('itinerary_id')
                                       ->label('Itinerary')
                                       ->options(Itinerary::get()->pluck('dropdown_name', 'id'))
                                       ->required(),
                Forms\Components\Select::make('time_of_day')
                                       ->options(Schedule::TIME_OF_DAY),
                Forms\Components\Select::make('destination_id')
                                       ->label('Destination')
                                       ->options(Destination::get()->pluck('dropdown_name', 'id'))
                                       ->searchable()
                                       ->preload()
                                       ->live()
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
                                       }),
                TextInput::make('price_per_pax')
                         ->disabled()
                         ->prefix('Rp'),
                TextInput::make('pax')
                         ->disabled(function (Forms\Get $get) {
                             return ! $get('price_per_pax');
                         })
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('destination.name')
                                         ->description(function (Schedule $record) {
                                             return $record->destination->destinationType->name;
                                         }),
                Tables\Columns\TextColumn::make('time_of_day')
                                         ->badge()
                                         ->color(fn(string $state): string => match ($state) {
                                             '10-morning' => 'morning',
                                             '20-afternoon' => 'afternoon',
                                             '30-evening' => 'evening',
                                             '40-night' => 'night',
                                         }),
                Tables\Columns\TextColumn::make('destination.price_per_pax')
                                         ->label('Cost')
                                         ->prefix('Rp')
                                         ->numeric(0, ',', '.'),
                Tables\Columns\TextInputColumn::make('pax'),
                Tables\Columns\TextColumn::make('total_cost')
                                         ->label('Total')
                                         ->prefix('Rp')
                                         ->numeric(0, ',', '.'),
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
                Tables\Grouping\Group::make('itinerary.date')
                                     ->titlePrefixedWithLabel(false)
                                     ->getTitleFromRecordUsing(function (Schedule $record) {
                                         return $record->itinerary->dropdown_name;
                                     })
                                     ->getDescriptionFromRecordUsing(function (Schedule $record) {
                                         return $record->itinerary->theme;
                                     })
                                     ->collapsible(),
            ])
            ->defaultGroup('itinerary.date')
            ->defaultSort('sort')
            ->reorderable('sort');
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
            'index'  => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit'   => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}