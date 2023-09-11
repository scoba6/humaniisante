<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Membre;
use App\Models\Famille;
use App\Models\Sinistre;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Prestataire;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\SinistreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SinistreResource\RelationManagers;
use App\Models\Acte;
use App\Models\Humpargen;

class SinistreResource extends Resource
{
    protected static ?string $model = Sinistre::class;

    
    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'SINISTRES';
    protected static ?string $modelLabel = 'Saisie';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('N° SIN'),
                Tables\Columns\TextColumn::make('prestataire.rsopre')->sortable()->label('PRESTATAIRE'),
                Tables\Columns\TextColumn::make('status')->sortable()->label('STATUT')
                    ->badge()
                    ->colors([
                        'danger' => 1,
                        'warning' => '2',
                        'success' => fn ($state) => in_array($state, ['3', '4']),
                    ]),
                Tables\Columns\TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE'),
                Tables\Columns\TextColumn::make('membre.nommem')->sortable()->label('MEMBRE'),
                Tables\Columns\TextColumn::make('mnttmo')->sortable()->label('TM'),
                Tables\Columns\TextColumn::make('mntass')->sortable()->label('P. HUMANIIS'),


            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSinistres::route('/'),
            'create' => Pages\CreateSinistre::route('/create'),
            'edit' => Pages\EditSinistre::route('/{record}/edit'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getFormSchema(string $section = null): array
    {
        if ($section === 'prestation') {
            return [

                Grid::make()
                ->schema([
                    Select::make('prestataire_id')->label('PRESTATAIRE')->options(Prestataire::all()->pluck('rsopre', 'id'))->columnSpan('full')
                        ->required()
                        ->searchable(),
                    DatePicker::make('datsai')->label('DATE DE SAISIE')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->native(false)
                        ->required(),
                    DatePicker::make('datmal')->label('DATE MALADIE')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->native(false)
                        ->required(),
                    
                    Select::make('acte_id')->label('NATURE ACTE')->options(Acte::all()->pluck('libact', 'id'))
                        ->required()
                        ->searchable(), 
                    Select::make('nataff_id')->label('NATURE AFFECTION')->options(Humpargen::all()->pluck('libpar', 'id'))
                        ->required()
                        ->searchable(), 
                    TextInput::make('mnttmo')->label('TM')
                        ->required(),
                    TextInput::make('mntass')->label('PART HUMANIIS')
                        ->required(),
        
                ]),
                  
            ];
        }

        return [
            Select::make('famille_id')->options(Famille::all()->pluck('nomfam', 'id'))->label('FAMILLE')
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('membre_id', null)),
           
            //Dependant select
            Select::make('membre_id')->label('MEMBRE')
                ->searchable()
                ->required()
                ->options(function ($get) {
                    $fam = Famille::find($get('famille_id'))?->id; //Famille
                    
                    if (!$fam) {
                        //return Option::all()->pluck('libopt', 'id');
                    }
                    return Membre::query()
                        ->where('famille_id', $fam)
                        ->pluck('nommem', 'id',);
                }),
    
        ];
    }
}
