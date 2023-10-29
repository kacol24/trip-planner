<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccomodationResource\Pages;
use App\Filament\Resources\AccomodationResource\RelationManagers;
use App\Models\Accomodation;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccomodationResource extends Resource
{
    protected static ?string $model = Accomodation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\Select::make('area_id')
                                       ->relationship('area', 'name')
                                       ->searchable()
                                       ->preload(),
                Forms\Components\TextInput::make('rate')
                                          ->label('Rate/room/night')
                                          ->numeric()
                                          ->columnSpan(2),
                Forms\Components\RichEditor::make('notes')
                                           ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\SelectColumn::make('area_id')
                                           ->label('Area')
                                           ->options(Area::get()->pluck('name', 'id')),
                Tables\Columns\TextColumn::make('rate')
                                         ->label('Rate/room/night')
                                         ->numeric()
                                         ->prefix('Rp'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAccomodations::route('/'),
        ];
    }
}
