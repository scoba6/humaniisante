<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Acte;
use Filament\Tables;
use App\Models\Membre;
use App\Models\Famille;
use App\Models\PCharge;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Prestataire;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PChargeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PChargeResource\RelationManagers;
use App\Models\User;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PChargeResource extends Resource
{
    protected static ?string $model = PCharge::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'SINISTRES';
    protected static ?string $modelLabel = 'Prises en charge';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'numsin';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('PRESTATAIRE & BENEFICIAIRE')
                            ->schema([
                                Forms\Components\Select::make('prestataire_id')
                                    ->label('PRESTATAIRE')
                                    ->options(Prestataire::all()->pluck('rsopre', 'id'))->columnSpan('full')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('famille_id')->options(Famille::all()->pluck('nomfam', 'id'))->label('FAMILLE')
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('membre_id', null)),
                                //Dependant select
                                Forms\Components\Select::make('membre_id')->label('MEMBRE')
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

                                /* Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full'), */
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('JUSTIFS')
                            ->schema([
                                FileUpload::make('attachements')->label('FICHIER JOINT')->columnSpan('full')
                                    ->directory('PC')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend('PC_'),
                                    )
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->previewable(true)
                                    ->openable()
                                    ->downloadable()
                                    ->visibility('public')
                            ])
                            ->collapsible(),

                        Forms\Components\Section::make('ACTES')
                            ->schema([
                                Repeater::make('actes')
                                    ->schema([
                                        Select::make('acte_id')->label('NATURE ACTE')->options(Acte::OrderBy('libact')->pluck('libact', 'id'))
                                            ->required()
                                            ->searchable(),
                                        TextInput::make('name')->required(),
                                        
                                    ])
                                    ->columns(2)
                                ])
                            ->columns(2),
                       
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Validité')
                            ->schema([
                                Forms\Components\DatePicker::make('datemi')
                                    ->label('Emission')
                                    ->default(now())
                                    ->required(),

                                Forms\Components\DatePicker::make('datexp')
                                    ->label('Expiration')
                                    ->default(Carbon::now()->addMonth())
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Intervenants')
                            ->schema([
                                Forms\Components\Select::make('redac_id')
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->label('REDACTEUR')
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('ctrler_id') 
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->label('CONTRÔLEUR')
                                    ->required()
                                    ->searchable(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPCharges::route('/'),
            'create' => Pages\CreatePCharge::route('/create'),
            'edit' => Pages\EditPCharge::route('/{record}/edit'),
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
