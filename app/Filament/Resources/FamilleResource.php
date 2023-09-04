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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FamilleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FamilleResource\RelationManagers;

class FamilleResource extends Resource
{
    protected static ?string $model = Famille::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'ADHESIONS';
    protected static ?string $navigationLabel = 'Familles';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'nomfam';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nomfam')->required()->label('NOM DE FAMILLE')->columnSpan('full'),
                Forms\Components\TextInput::make('vilfam')->required()->label('VILLE'),
                Forms\Components\TextInput::make('payfam')->required()->label('PAYS'),
                Forms\Components\Textarea::make('adrfam')->required()->label('ADRESSE (TEL - MAIL)')->columnSpan('full'),
                Select::make('commercial_id')->label('COMMERCIAL')->required()->options(Commercial::all()->pluck('nomcom', 'id'))->columnSpan('full')
                    ->relationship('commercial', 'id')
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomfam')->label('NOM DE FAMILLE')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('adrfam')->label('ADRESSE')->sortable(),
                Tables\Columns\TextColumn::make('vilfam')->label('VILLE')->sortable(),
                Tables\Columns\TextColumn::make('payfam')->label('PAYS')->sortable(),
                Tables\Columns\TextColumn::make('commercial.nomcom')->label('COMMERCIAL')->sortable(),
               
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ManageFamilles::route('/'),
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
