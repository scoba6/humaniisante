<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Groupe;
use App\Models\Membre;
use App\Models\Option;
use App\Models\Famille;
use App\Models\Formule;
use App\Models\Qualite;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\MembreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MembreResource\RelationManagers;

class MembreResource extends Resource
{
    protected static ?string $model = Membre::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'ADHESIONS';
    protected static ?string $navigationLabel = 'Ayant droits';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('famille_id')->label('FAMILLE')->required()->options(Famille::all()->pluck('nomfam', 'id'))->columnSpan('full')->searchable(),
                TextInput::make('nommem')->required()->label('NOM PRENOM'), //->columnSpan('full'),
                Select::make('qualite_id')->label('QUALITE')->required()->options(Qualite::all()->pluck('libqlt', 'id')),
                DateTimePicker::make('datnai')->label('DATE DE NAISSANCE')->displayFormat('d/m/Y')->maxDate(now())->required()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $get) {
                        $dateOfBirth = $get('datnai');
                        $age = Carbon::now()->diffInYears($dateOfBirth);
                        $set('agemem', $age);
                    }),
                TextInput::make('agemem')->label('AGE')->disabled(),

                Select::make('formule_id')
                    ->label('FORMULE')
                    ->options(Formule::all()->pluck('libfrm', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('option_id', null)),
                Select::make('sexmem_id')
                    ->label('CATEGORIE')
                    ->options(Groupe::all()->pluck('libsxg', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('option_id', null)),


                //Dependant select
                Select::make('option_id')
                    ->label('Option')
                    ->required()
                    ->options(function (\Filament\Forms\Get $get) {
                        $frm = Formule::find($get('formule_id'))?->id; //Formule
                        $grp = Groupe::find($get('sexmem_id'))?->id; // Categorie
                        $age = $get('agemem');

                        if (!$frm) {
                            return Option::all()->pluck('libopt', 'id');
                        }
                        return Option::query()
                            ->where('formule_id', $frm)
                            ->where('sexgrp_id', $grp)
                            ->where('agemin', '<=', $age)
                            ->where('agemax', '>=', $age)
                            ->pluck('libopt', 'id',);
                    }),

                TextInput::make('matmem')->label('MATRICULE')->disabled(),
                DateTimePicker::make('valfrm')->label('VALIDITE FORMULE')->displayFormat('d/m/Y')->maxDate(now())->required()->columnSpan('full'),
                Fieldset::make('OPTIONS')
                    ->schema([
                        Toggle::make('framem')->inline()->onColor('success')->offColor('danger')->label('FRAIS ADHESION'),
                        Toggle::make('optmem')->inline()->onColor('success')->offColor('danger')->label('RACHAT OPTIQUE'),
                        Toggle::make('denmem')->inline()->onColor('success')->offColor('danger')->label('RACHAT DENTISTERIE'),
                    ])
                    ->columns(3),
                Textarea::make('commem')->label('COMMENTAIRES')->columnSpan('full'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE')->searchable(),
                Tables\Columns\TextColumn::make('qualite.libqlt')->sortable()->label('QUALITE')->searchable(),
                Tables\Columns\TextColumn::make('formule.libfrm')->sortable()->label('FORMULE'),
                Tables\Columns\TextColumn::make('nommem')->sortable()->label('NOM PRENOM')->searchable(),
                Tables\Columns\TextColumn::make('matmem')->sortable()->label('MATRICULE'),
                Tables\Columns\TextColumn::make('datnai')->sortable()->label('DATE DE NAISSANCE')->datetime('d/m/Y'),
                Tables\Columns\TextColumn::make('agemem')->sortable()->label('AGE'),
                Tables\Columns\TextColumn::make('sexmem_id')->sortable()->label('SEXE'),
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
            'index' => Pages\ManageMembres::route('/'),
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
