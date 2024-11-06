<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Enums\ModReg;
use Filament\Forms\Set;
use App\Models\Sinistre;
use Filament\Forms\Form;
use App\Models\Reglement;
use Filament\Tables\Table;
use App\Models\Prestataire;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ReglementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReglementResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ReglementResource extends Resource
{
    protected static ?string $model = Reglement::class;

    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'SINISTRES';
    protected static ?string $modelLabel = 'Règlements';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'numreg';
    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Section::make()
                        ->schema(static::getDetailsFormSchema())
                        ->columns(2),

                    Section::make('Détails des sinistres réglés')
                        ->schema([
                            static::getItemsRepeater(),
                        ]),
                ])->columnSpan(['lg' => fn (?Reglement $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                ->schema([
                    Placeholder::make('created_at')
                        ->label('Saisi le')
                        ->content(fn (Reglement $record): ?string => $record->created_at?->diffForHumans()),

                    Placeholder::make('created_by')
                        ->label('Par')
                        ->content(fn (Reglement $record): ?string => User::find($record->updated_by)?->name),

                    Placeholder::make('updated_at')
                        ->label('Derniere Modification')
                        ->content(fn (Reglement $record): ?string => $record->updated_at?->diffForHumans()),

                    Placeholder::make('updated_by')
                        ->label('Par')
                        ->content(fn (Reglement $record): ?string => User::find($record->updated_by)?->name),
                ])
                ->columnSpan(['lg' => 1])
                ->hidden(fn (?Reglement $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numreg')->sortable()->label('N° REGLEMENT'),
                TextColumn::make('prestataire.rsopre')->sortable()->label('PRESTATAIRE'),
                TextColumn::make('modreg')->sortable()->label('MODE DE REGLEMENT'),
                TextColumn::make('mntreg')->sortable()->label('MNT REGLEMENT')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReglements::route('/'),
            'create' => Pages\CreateReglement::route('/create'),
            'edit' => Pages\EditReglement::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('numreg')->label('N° DE REGLEMENT')
                ->disabled()
                //->required()
                ->maxLength(32),

            TextInput::make('mntreg')->label('MONTANT DU REGLEMENT')
                ->disabled(),
                //->required(),

            Select::make('prestataire_id')->label('PRESTATAIRE')
                ->options(Prestataire::all()->pluck('rsopre', 'id'))
                ->searchable()
                ->required(),

            ToggleButtons::make('modreg')->label('MODE DE REGLEMENT')
                ->inline()
                ->options(ModReg::class)
                ->required(),

            FileUpload::make('attachements')->label('DOCUMENT')->columnSpan('full')
                ->directory('REGLEMENTS')
                ->getUploadedFileNameForStorageUsing(
                    fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                        ->prepend('REG_'))
                ->acceptedFileTypes(['application/pdf'])
                ->previewable(true)
                ->openable()
                ->downloadable()
                ->visibility('public'),

            MarkdownEditor::make('comment')->label('COMMENTAIRE')->columnSpanFull()
                ->columnSpan('full'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('sinistres')
            ->relationship()
            ->schema([
                Select::make('sinistre_id')->label('SINISTRE')
                    ->options(Sinistre::query()->where('prestataire_id','=',)->pluck('numsin', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('mntass', Sinistre::find($state)?->mntass ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),

                TextInput::make('mntass')
                    ->label('Montant à régler')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 8,
            ])
            ->required()
            ->addActionLabel('Ajouter un sinistre');
    }
}
