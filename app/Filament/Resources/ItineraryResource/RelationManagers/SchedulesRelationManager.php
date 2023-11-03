<?php

namespace App\Filament\Resources\ItineraryResource\RelationManagers;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Form $form): Form
    {
        return $form
            ->schema(ScheduleResource::getSchema($related = true));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('destination.name')
            ->paginated(false)
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('destination.name')
                                         ->description(function (Schedule $record) {
                                             return $record->destination->destinationType->name;
                                         }),
                Tables\Columns\SelectColumn::make('time_of_day')
                                           ->options(Schedule::TIME_OF_DAY)
                                           ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')
                                         ->html(),
                Tables\Columns\TextInputColumn::make('pax')
                                              ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cost_calculator')
                                         ->toggleable()
                                         ->label('Total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                                             ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                //
            ]);
    }
}
