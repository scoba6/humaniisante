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
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\MembreResource\Pages;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\MembreResource\Pages\EditMembre;
use App\Filament\Resources\MembreResource\RelationManagers;
use App\Filament\Resources\MembreResource\Pages\ListMembres;
use App\Filament\Resources\MembreResource\Pages\CreateMembre;

class MembreResource extends Resource
{
    protected static ?string $model = Membre::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'ADHESIONS';
    protected static ?string $navigationLabel = 'Ayant droits';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $recordTitleAttribute = 'matmem';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('famille_id')->label('FAMILLE')->required()->options(Famille::all()->pluck('nomfam','id'))->columnSpan('full')->searchable(),
                TextInput::make('nommem')->required()->label('NOM PRENOM'), //->columnSpan('full'),
                Select::make('qualite_id')->label('QUALITE')->required()->options(Qualite::all()->pluck('libqlt', 'id')),
                DatePicker::make('datnai')->label('DATE DE NAISSANCE')->displayFormat('d/m/Y')->maxDate(now())->required()
                    ->reactive()
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $get) {
                        $dateOfBirth = $get('datnai');
                        $age = Carbon::now()->diffInYears($dateOfBirth);
                        //dd($age);
                        $set('agemem', $age);
                    }),
                TextInput::make('agemem')->label('AGE')->readOnly(),

                Select::make('formule_id')
                    ->label('FORMULE')
                    ->options(Formule::all()->pluck('libfrm', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('option_id', null)),
                Select::make('groupe_id')
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
                        $grp = Groupe::find($get('groupe_id'))?->id; // Categorie
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
                DatePicker::make('valfrm')->label('VALIDITE FORMULE')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false),
                DatePicker::make('datret')->label('RETIRE LE')
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false),
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
                TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE')->searchable(),
                TextColumn::make('qualite.libqlt')->sortable()->label('QUALITE')->searchable(),
                TextColumn::make('formule.libfrm')->sortable()->label('FORMULE'),
                TextColumn::make('nommem')->sortable()->label('NOM PRENOM')->searchable(),
                TextColumn::make('famille.numcdg')->sortable()->label('CDG')->searchable(),
                TextColumn::make('matmem')->sortable()->label('MATRICULE')->searchable(),
                TextColumn::make('datnai')->sortable()->label('DATE DE NAISSANCE')->datetime('d/m/Y'),
                TextColumn::make('agemem')->sortable()->label('AGE'),
                TextColumn::make('groupe.libsxg')->sortable()->label('SEXE'),
                ToggleColumn::make('active')->label('ACTIF')->sortable()
                    ->onColor('success')
                    ->offColor('danger'),
                //IconColumn::make('active')->label('ACTIF')->boolean()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('QR CODE')
                    ->url(fn (Membre $record) => static::getUrl('qrcode', [$record->id]))
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-qr-code')
                    ->iconButton(),
                Tables\Actions\EditAction::make()->label(''),


            ])
            ->groupedBulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CotisationsRelationManager::class,
            RelationManagers\SinistresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembres::route('/'),
            'create' => Pages\CreateMembre::route('/create'),
            'edit' => Pages\EditMembre::route('/{record}/edit'),
            'qrcode' => Pages\ViewQrCode::route('/{record}/qrcode'), // Page d'affichage du QR CODE
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
