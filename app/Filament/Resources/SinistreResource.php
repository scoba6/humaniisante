<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Acte;
use App\Models\User;
use Filament\Tables;
use App\Models\Membre;
use App\Models\Famille;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\SinStatut;
use App\Models\Sinistre;
use Filament\Forms\Form;
use App\Models\Humpargen;
use Filament\Tables\Table;
use App\Models\Prestataire;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\SinistreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SinistreResource\RelationManagers;
use App\Models\Formule;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\CommonMark\Renderer\Block\ThematicBreakRenderer;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SinistreResource extends Resource
{
    protected static ?string $model = Sinistre::class;


    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'SINISTRES';
    protected static ?string $modelLabel = 'Saisie';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'numpch';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema(static::getFormSchema())
                        ->columns(2),

                    Forms\Components\Section::make('Prestation')
                        ->schema(static::getFormSchema('prestation')),
                ])
                ->columnSpan(['lg' => fn (?Sinistre $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                ->schema([
                    Placeholder::make('created_at')
                        ->label('Saisi le')
                        ->content(fn (Sinistre $record): ?string => $record->created_at?->diffForHumans()),

                    Placeholder::make('created_by')
                        ->label('Par')
                        ->content(fn (Sinistre $record): ?string => User::find($record->updated_by)?->name),

                    Placeholder::make('updated_at')
                        ->label('Derniere Modification')
                        ->content(fn (Sinistre $record): ?string => $record->updated_at?->diffForHumans()),

                    Placeholder::make('updated_by')
                        ->label('Par')
                        ->content(fn (Sinistre $record): ?string => User::find($record->updated_by)?->name),

                    Select::make('status')->label('STATUT')
                        ->options([
                            '1' => 'A VERIFIER',
                            '2' => 'VERIFIE',
                            '3' => 'REGLE',
                        ])
                        //->required()

                ])
                ->columnSpan(['lg' => 1])
                ->hidden(fn (?Sinistre $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numsin')->sortable()->label('N° SIN'),
                Tables\Columns\TextColumn::make('prestataire.rsopre')->sortable()->label('PRESTATAIRE'),
                Tables\Columns\TextColumn::make('status')->sortable()->label('STATUT')
                    ->badge()
                    ->colors([
                        'danger' => '1',
                        'warning' => '2',
                        'success' => fn ($state) => in_array($state, ['3', '4']),
                    ]),
                Tables\Columns\TextColumn::make('famille.nomfam')->sortable()->label('FAMILLE'),
                Tables\Columns\TextColumn::make('membre.nommem')->sortable()->label('MEMBRE'),
                Tables\Columns\TextColumn::make('mnttmo')->sortable()->label('TM'),
                Tables\Columns\TextColumn::make('mntass')->sortable()->label('P. HUMANIIS'),
                //Tables\Columns\TextColumn::make('attachements')->sortable()->label('FICHIERS'),



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
                    Select::make('typsin')->label('TYPE')
                    ->options([
                        '1' => 'AMBULATOIRE',
                        '2' => 'HOSPITALISATION',
                        ])
                        ->required(),
                    Select::make('prestataire_id')->label('PRESTATAIRE')->options(Prestataire::all()->pluck('rsopre', 'id'))
                        ->required()
                        ->searchable(),
                    Section::make()
                        ->schema([
                            TextInput::make('mnttot')->label('MONTANT TOAL')
                                ->required(),
                            TextInput::make('mnttmo')->label('TM')
                                ->readOnly(),
                            TextInput::make('mntass')->label('PART HUMANIIS')
                                  ->readOnly(),
                        ])->columns(3),
                    DatePicker::make('datsai')->label('DATE DE SAISIE')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->native(false)
                        ->required(),
                    DatePicker::make('datmal')->label('DATE MALADIE')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->native(false)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $mbre = $get('membre_id');
                            $type = $get('typsin'); //Type de sinistre
                            $mnto = $get('mnttot');
                            $frml = Membre::find($mbre)?->formule_id; // La formule du membre

                            switch ($type) {
                                case 1:
                                    $taux = Formule::find($frml)?->tauamb; // Taux de couverture en fonction du type de sinistre
                                    break;
                                case 2:
                                    $taux = Formule::find($frml)?->tauhos; // Taux de couverture en fonction du type de sinistre
                                    break;
                                default:
                                    $taux = Formule::find($frml)?->tauamb; // par défaut on est jr en ambulatoire
                            }

                            //Part HUMANIIS
                            $parh =  ($mnto * $taux)/100;

                            //le ticket modérateur
                            $timo = $mnto - $parh;

                            $set('mnttmo',  $timo);
                            $set('mntass',  $parh);
                        }),
                    Select::make('acte_id')->label('NATURE ACTE')->options(Acte::OrderBy('libact')->pluck('libact', 'id'))
                        ->required()
                        ->searchable(),
                    Select::make('nataff_id')->label('NATURE AFFECTION')->options(Humpargen::query()->whereIn('id', [4,5,6])->pluck('LIBPAR', 'id'))
                        ->required()
                        ->searchable(),

                    FileUpload::make('attachements')->label('FICHIER JOINT')->columnSpan('full')
                        ->directory('SINISTRES')
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend('SIN_'),
                        )
                        ->acceptedFileTypes(['application/pdf'])
                        ->previewable(true)
                        ->openable()
                        ->downloadable()
                        ->visibility('public')
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
