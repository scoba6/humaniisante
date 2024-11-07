<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Acte;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ActeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActeResource\RelationManagers;

class ActeResource extends Resource
{
    protected static ?string $model = Acte::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'PARAMETRES';
    protected static ?string $navigationLabel = 'Actes médicaux';
    //protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('libact')->label('LIBELLE')
                    ->required()
                    ->maxLength(100)
                    ->default('text'),

                TextInput::make('mntact')->label('MONTANT')
                    ->default(0),
                Toggle::make('plafon')->label('PLAFONNE')
                    ->required()
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('libact')->label('LIBELLE')
                    ->searchable(),
                TextColumn::make('mntact')->label('MONTANT'),
                IconColumn::make('plafon')->label('PLAFONNE')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('danger')


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActes::route('/'),
        ];
    }
}
