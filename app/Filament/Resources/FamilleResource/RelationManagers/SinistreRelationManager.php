<?php

namespace App\Filament\Resources\FamilleResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SinistreRelationManager extends RelationManager
{
    protected static string $relationship = 'sinistre';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numsin')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numsin')
            ->columns([
                TextColumn::make('numsin'),
                TextColumn::make('prestataire.rsopre')->sortable()->label('PRESTATAIRE'),
                TextColumn::make('status')->sortable()->label('STATUT')
                    ->badge()
                    ->colors([
                        'danger' => '1',
                        'warning' => '2',
                        'success' => fn ($state) => in_array($state, ['3', '4']),
                    ]),
                TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE'),
                TextColumn::make('membre.nommem')->sortable()->label('MEMBRE'),
                
                TextColumn::make('mnttmo')->sortable()->label('TM')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('XAF'),
                    ]),
                TextColumn::make('mntass')->sortable()->label('P. HUMANIIS')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('XAF'),
                    ]),
            ])
            ->filters([
                 //ExportBulkAction::make(),

            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
