<?php

namespace App\Filament\Resources\MembreResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Membre;
use App\Models\Option;
use Filament\Forms\Form;
use App\Models\Humpargen;
use Filament\Tables\Table;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CotisationsRelationManager extends RelationManager
{
    protected static string $relationship = 'cotisations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('datcot')->label('DATE DE COTISATION')->displayFormat('d/m/Y')->maxDate(now())->required(),
                DatePicker::make('datval')->label('DATE DE VALIDITE')->displayFormat('d/m/Y')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (RelationManager $livewire, \Filament\Forms\Set $set) {
                        $mem = $livewire->ownerRecord->id; //Membre
                        $opt = $livewire->ownerRecord->option_id; //Option
                        $coti = Option::find($opt)?->mntxaf; //Montant de la coti
                        $fra = Humpargen::find(1)->tauval; //Montant des frais d'adhésion
                        //dd($fra);

                        $rht_opt =  Membre::find($mem)?->optmem; //Rachat optique
                        if ($rht_opt) {
                            $coti_opt = Option::find($opt)?->mntopx; //Montant du rachat optique
                            $coti = $coti + $coti_opt; //Cotisation de l'option + le rachat optique
                        }

                        $rht_dnt =  Membre::find($mem)?->denmem; //Rachat dentisterie
                        if ($rht_dnt) {
                            $coti_dnt = Option::find($opt)?->mntdnx; //Montant du rachat de la dentisterie
                            $coti = $coti + $coti_dnt; //Cotisation de l'option + le rachat optique
                        }

                        //TPS
                        $tps = round($coti * 9.5) / 100;

                        //CSS
                        $css = round($coti * 1) / 100;


                        $frs_adh = Membre::find($mem)?->framem; //Frais
                        if ($frs_adh) {
                            $adh = 0; //Cotisation de l'option + frais d'adhésion
                        } else {
                            $adh = 5000;
                        }
                        
                        //Accessoires
                        $acc = 10000; 

                        $ttc = $coti + $tps + $css + $adh + $acc;

                        $set('mntcot', $coti);
                        $set('mnttps', round($tps));
                        $set('mntcss', $css);
                        $set('mntadh', $adh);
                        $set('mntacc', $acc);
                        $set('mntttc', round($ttc));
                    }),

                Fieldset::make('DETAILS DE LA COTISATION')
                    ->schema([
                        TextInput::make('mntcot')->readOnly()->label('MOTANT HT'),
                        TextInput::make('mnttps')->readOnly()->label('TPS'),
                        TextInput::make('mntcss')->readOnly()->label('CSS'),
                        TextInput::make('mntadh')->readOnly()->label('ADH'),
                        TextInput::make('mntacc')->readOnly()->label('ACC'),
                        TextInput::make('mntttc')->readOnly()->label('MONTANT TTC')->columnSpanFull()
                    ])->columns(5),

                Textarea::make('detcot')->label('COMMENTAIRES')->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('mntcot')
            ->columns([
                Tables\Columns\TextColumn::make('mntcot')->label('MNT COT')->money('XAF'),
                Tables\Columns\TextColumn::make('mnttps')->label('MNT TPS')->money('XAF'),
                Tables\Columns\TextColumn::make('mntcss')->label('MNT CSS')->money('XAF'),
                Tables\Columns\TextColumn::make('mntadh')->label('MNT ADH')->money('XAF'),
                Tables\Columns\TextColumn::make('mntacc')->label('MNT ACC')->money('XAF'),
                Tables\Columns\TextColumn::make('mntttc')->label('MNT TTC')->money('XAF'),
                Tables\Columns\TextColumn::make('datcot')->sortable()->label('DATE')->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('datval')->sortable()->label('VALIDITE')->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('detcot')->label('OBSERVATIONS'),
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
}
