<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Famille;
use Filament\Forms\Form;
use App\Models\Commercial;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FamilleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\FamilleResource\RelationManagers;
use App\Filament\Resources\FamilleResource\RelationManagers\SinistreRelationManager;

class FamilleResource extends Resource
{
    protected static ?string $model = Famille::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'ADHESIONS';
    protected static ?string $navigationLabel = 'Entreprises/Familles';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'nomfam';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomfam')->required()->label('FAMILLE/RAISON')->columnSpan('full'),
                Select::make('statut')
                    ->options([
                        '1' => 'PARTICULIER',
                        '2' => 'ENTREPRISE',
                    ])
                    ->label('STATUT')
                    ->required()
                    ->native(true),
                TextInput::make('numcdg')->label('CONVENTION DE GESTION')
                    ->readOnly(),
                TextInput::make('vilfam')->required()->label('VILLE'),
                TextInput::make('payfam')->required()->label('PAYS'),
                Textarea::make('adrfam')->required()->label('ADRESSE')->columnSpan('full'),
                Select::make('commercial_id')->label('COMMERCIAL')->required()->options(Commercial::all()->pluck('nomcom', 'id'))->columnSpan('full')
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomfam')->label('NOM DE FAMILLE')->searchable()->sortable(),
                SelectColumn::make('statut')
                ->options([
                    '1' => 'PARTICULIER',
                    '2' => 'ENTREPRISE',
                ])
                ->disabled()
                ->sortable()
                ->selectablePlaceholder(false)
                ->label('STATUT'),
                TextColumn::make('numcdg')->label('N° DE CONVENTION')->searchable()->sortable(),
                TextColumn::make('adrfam')->label('ADRESSE')->sortable(),
                TextColumn::make('vilfam')->label('VILLE')->sortable(),
                TextColumn::make('payfam')->label('PAYS')->sortable(),
                TextColumn::make('commercial.nomcom')->label('COMMERCIAL')->sortable(),
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
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SinistreRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilles::route('/'),
            'create' => Pages\CreateFamille::route('/create'),
            'edit' => Pages\EditFamille::route('/{record}/edit'),
        ];
    }
}
