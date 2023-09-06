<?php

namespace App\Filament\Resources\FormuleResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Groupe;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sexgrp_id')->label('CATEGORIE')->required()->options(Groupe::all()->pluck('libsxg', 'id'))->columnSpanFull(),
                Forms\Components\TextInput::make('libopt')->label('LIBELLE')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('agemin')->label('AGE MIN'),
                TextInput::make('agemax')->label('AGE MAX'),
                TextInput::make('mntxaf')->label('MOTANT XAF'),
                TextInput::make('mnteur')->label('MOTANT EUR'),

                Forms\Components\Section::make('RACHAT OPTIQUE')
                    ->schema([
                        TextInput::make('mntopx')->label('MOTANT XAF'),
                        TextInput::make('mntope')->label('MOTANT EUR'),
                    ])->collapsible()
                    ->collapsed()
                    ->columns(2),
                
                Forms\Components\Section::make('RACHAT DENTISTERIE')
                  ->schema([
                    TextInput::make('mntdnx')->label('MOTANT XAF'),
                    TextInput::make('mntdne')->label('MOTANT EUR'),

                  ])->collapsible()
                    ->collapsed()
                    ->columns(2),
                
                Textarea::make('dtlopt')->label('DETAILS OPTION')->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('libopt')
            ->columns([
                Tables\Columns\TextColumn::make('libopt')->label('LIBELLE'),
                TextColumn::make('agemin')->label('AGE MIN')->sortable(),
                TextColumn::make('agemax')->label('AGE MAX')->sortable(),
                TextColumn::make('groupe.libsxg')->label('CATEGORIE')->sortable(),

                TextColumn::make('mntxaf')->label('MOTANT XAF')->money('XAF'),
                TextColumn::make('mnteur')->label('MOTANT EUR')->money('eur'),

                TextColumn::make('mntopx')->label('RACHAT OPT.')->money('XAF'),
                TextColumn::make('mntdnx')->label('RACHAT DEN.')->money('XAF'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
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
}
