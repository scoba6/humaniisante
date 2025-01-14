<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Localite;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LocaliteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LocaliteResource\RelationManagers;

class LocaliteResource extends Resource
{
    protected static ?string $model = Localite::class;
    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'PARAMETRES';
    protected static ?string $navigationLabel = 'Localités';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('payloc')->required()->label('PAYS'),
                TextInput::make('libloc')->required()->label('VILLE'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payloc')->label('PAYS')->searchable()->sortable(),
                TextColumn::make('libloc')->label('VILLE')->searchable()->sortable(),
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
            'index' => Pages\ManageLocalites::route('/'),
        ];
    }
}
