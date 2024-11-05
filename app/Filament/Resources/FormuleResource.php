<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Formule;
use App\Models\Territo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormuleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormuleResource\RelationManagers;

class FormuleResource extends Resource
{
    protected static ?string $model = Formule::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'PARAMETRES';
    protected static ?string $navigationLabel ='Formules';
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('libfrm')->required()->label('FORMULE'),
                Select::make('territo_id')->label('TERRITORIALITE')->options(Territo::all()->pluck('libter', 'id'))
                    ->required()
                    ->searchable(),
                Toggle::make('ambfrm')->required()->label('AMBULATOIRE'),
                Fieldset::make('TAUX DE PRISE EN CHARGE')
                    ->schema([
                        TextInput::make('tauamb')->required()->label('AMBULATOIRE'),
                        TextInput::make('tauhos')->required()->label('HOSPITALISATION'),
                    ])
                    ->columns(2),
                Fieldset::make('LIMITES ET PLAFONDS ANNNUELS')
                ->schema([
                    TextInput::make('limacc')->required()->label('ACCOUCHEMENTS')
                        ->numeric(),
                    TextInput::make('limhos')->required()->label('HOSPITALISATIONS')
                        ->numeric(),
                    TextInput::make('limbio')->required()->label('EXAMEN DE BIOLOGIE')
                        ->numeric(),
                    TextInput::make('limrad')->required()->label('EXAMEN DE RADIOLOGIE')
                        ->numeric(),
                    TextInput::make('limchr')->required()->label('CHAMBRES')
                        ->numeric(),
                    TextInput::make('limpla')->required()->label('PLANFOND GLOBAL')
                        ->numeric(),
                    TextInput::make('limact')->required()->label('AUTRES ACTES')
                        ->numeric()
                        ,
                ])
                    ->columns(2),
                Textarea::make('comfrm')->required()->label('DETAILS FORMULE')->columnSpan('full'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('libfrm')->sortable()->label('FORMULE'),
                TextColumn::make('tauamb')->sortable()->label('AMB'),
                TextColumn::make('tauhos')->sortable()->label('HOS'),
                TextColumn::make('limpla')->sortable()->label('PLANFOND/AN'),
                TextColumn::make('comfrm')->sortable()->label('DETAILS'),
                IconColumn::make('ambfrm')->label('AMBULATOIRE')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
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
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\OptionsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormules::route('/'),
            'create' => Pages\CreateFormule::route('/create'),
            'edit' => Pages\EditFormule::route('/{record}/edit'),
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
