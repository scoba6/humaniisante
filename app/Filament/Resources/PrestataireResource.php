<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Prestataire;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PrestataireResource\Pages;
use App\Filament\Resources\PrestataireResource\RelationManagers;

class PrestataireResource extends Resource
{
    protected static ?string $model = Prestataire::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'PARAMETRES';
    protected static ?string $navigationLabel = 'Prestataires';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rsopre')->required()->label('RAISON SOCIALE')->columnSpan('full'),
                Textarea::make('adrpre')->required()->label('ADRESSE')->columnSpan('full'),
                TextInput::make('telpre')->required()->label('TELEPHONE'),
                TextInput::make('maipre')->email()->label('E-MAIL'),
                Radio::make('natpre')->label('TYPE DE PRESTATIONS')
                    ->options([
                        '1' => 'PUBLIQUE',
                        '2' => 'PRIVE'
                    ])
                    ->inline()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rsopre')->label('RAISON SOCIALE')->sortable(),
                Tables\Columns\TextColumn::make('adrpre')->label('ADRESSE')->sortable(),
                Tables\Columns\TextColumn::make('telpre')->label('TELEPHONE')->sortable(),
                Tables\Columns\TextColumn::make('maipre')->label('MAIL')->sortable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePrestataires::route('/'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
