<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Destination;
use App\Models\DestinationType;
use App\Models\Itinerary;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
            ->schema(
                array_merge([
                    Forms\Components\Select::make('itinerary_id')
                                           ->label('Itinerary')
                                           ->options(Itinerary::get()->pluck('dropdown_name', 'id'))
                                           ->required()
                                           ->columnSpan([
                                               'default' => 2,
                                               'sm'      => 1,
                                           ]),
                ], self::getSchema())
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('destination.name')
                                         ->description(function (Schedule $record) {
                                             if ($record->destination->destination_type_id == DestinationType::TYPE_OTW) {
                                                 return null;
                                             }

                                             return $record->destination->destinationType->name;
                                         }),
                Tables\Columns\TextColumn::make('notes')
                                         ->html(),
                Tables\Columns\TextColumn::make('destination.price_per_pax')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->label('Cost')
                                         ->prefix('Rp')
                                         ->numeric(0, ',', '.'),
                Tables\Columns\TextInputColumn::make('pax')
                                              ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_cost')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->label('Total')
                                         ->prefix('Rp')
                                         ->numeric(0, ',', '.'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('itinerary_id')
                                           ->label('Day')
                                           ->options(Itinerary::get()->pluck('dropdown_name', 'id'))
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
            'index' => Pages\ManageSchedules::route('/'),
            //'create' => Pages\CreateSchedule::route('/create'),
            //'edit'   => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getSchema($related = false)
    {
        return [
            Forms\Components\Select::make('destination_id')
                                   ->label('Destination')
                                   ->searchable()
                                   ->preload()
                                   ->live()
                                   ->columnSpan(2)
                                   ->relationship('destination')
                                   ->getOptionLabelFromRecordUsing(function (Destination $record) {
                                       return $record->dropdown_name;
                                   })
                                   ->afterStateHydrated(function (Set $set, Get $get, ?string $state) {
                                       $entity = Destination::find($state);
                                       $pricePerPax = optional($entity)->price_per_pax;
                                       $pax = $get('pax');
                                       $totalPrice = optional($entity)->price_per_pax * $pax;

                                       $set('price_per_pax', number_format($pricePerPax, 0, ',', '.'));
                                       $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                       $set('destination_notes', optional($entity)->notes);
                                   })
                                   ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                       $entity = Destination::find($state);
                                       $pricePerPax = optional($entity)->price_per_pax;
                                       $pax = $get('pax');
                                       $totalPrice = optional($entity)->price_per_pax * $pax;

                                       $set('price_per_pax', number_format($pricePerPax, 0, ',', '.'));
                                       $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                       $set('destination_notes', optional($entity)->notes);
                                   })
                                   ->createOptionForm(DestinationResource::getSchema())
                                   ->editOptionForm(DestinationResource::getSchema())
                                   ->required(),
            Forms\Components\Select::make('time_of_day')
                                   ->options(Schedule::TIME_OF_DAY)
                                   ->required()
                                   ->columnSpan([
                                       'default' => 2,
                                       'sm'      => 1,
                                   ]),
            TextInput::make('notes')
                     ->columnSpan([
                         'default' => 2,
                         'sm'      => 1,
                     ]),
            Grid::make(3)
                ->columnSpan(2)
                ->schema([
                    Grid::make([
                        'default' => 3,
                        'sm'      => 2,
                    ])
                        ->columnSpan([
                            'default' => 3,
                            'sm'      => 2,
                        ])
                        ->schema([
                            TextInput::make('price_per_pax')
                                     ->columnSpan([
                                         'default' => 2,
                                         'sm'      => 1,
                                     ])
                                     ->disabled()
                                     ->prefix('Rp'),
                            TextInput::make('pax')
                                     ->columnSpan([
                                         'default' => 1,
                                         'sm'      => 1,
                                     ])
                                     ->disabled(function (Get $get) {
                                         return ! $get('destination_id');
                                     })
                                     ->numeric()
                                     ->live()
                                     ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                         $pricePerPax = str_replace('.', '', $get('price_per_pax'));
                                         $pax = $state;
                                         $totalPrice = $pricePerPax * $pax;

                                         $set('total_price', number_format($totalPrice, 0, ',', '.'));
                                     }),
                        ]),
                    TextInput::make('total_price')
                             ->prefix('Rp')
                             ->disabled(),
                    Forms\Components\RichEditor::make('destination_notes')
                                               ->disabled(),
                ]),
        ];
    }
}
