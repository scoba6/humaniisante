<?php

namespace App\Filament\Resources\MembreResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

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
                TextColumn::make('mnttot')->sortable()->label('TOTAL')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('XAF'),
                    ]),
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public function isReadOnly(): bool
    {
        return true;
    }
}
