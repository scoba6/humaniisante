<?php

namespace App\Filament\Resources\MembreResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SinistresRelationManager extends RelationManager
{
    protected static string $relationship = 'sinistres';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
             /*    Forms\Components\TextInput::make('numsin')->label('N° SIN')
                    ->required()
                    ->maxLength(255), */
                
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numsin')
            ->columns([
                Tables\Columns\TextColumn::make('numsin')->label('N° SIN'),
                Tables\Columns\TextColumn::make('prestataire.rsopre')->sortable()->label('PRESTATAIRE'),
                Tables\Columns\TextColumn::make('status')->sortable()->label('STATUT')
                    ->badge()
                    ->colors([
                        'danger' => '1',
                        'warning' => '2',
                        'success' => fn ($state) => in_array($state, ['3', '4']),
                    ]),
                //Tables\Columns\TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE'),
                //Tables\Columns\TextColumn::make('membre.nommem')->sortable()->label('MEMBRE'),
                Tables\Columns\TextColumn::make('mnttmo')->sortable()->label('TM')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('XAF'),
                    ]),
                Tables\Columns\TextColumn::make('mntass')->sortable()->label('P. HUMANIIS')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('XAF'),
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
