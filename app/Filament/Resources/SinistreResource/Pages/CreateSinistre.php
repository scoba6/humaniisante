<?php

namespace App\Filament\Resources\SinistreResource\Pages;

use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SinistreResource;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateSinistre extends CreateRecord
{

    use HasWizard;
    protected static string $resource = SinistreResource::class;
    protected static ?string $title = 'Saisie de sinistre';

    protected function afterCreate(): void
    {
        $sinistre = $this->record;

        Notification::make()
            ->title('Nouveau Sinistre')
            ->icon('heroicon-o-shopping-bag')
             ->body("**Sinistre {$sinistre->numsin} créé**")
            ->actions([
                Action::make('View')
                    ->url(SinistreResource::getUrl('edit', ['record' => $sinistre])),
            ])
            ->sendToDatabase(auth()->user());
    }


    protected function getSteps(): array
    {
        return [
           /*  Step::make('Details assuré')
                ->schema([
                    Section::make()->schema(SinistreResource::getFormSchema())->columns(),
                ]), */

            Step::make('Détails Adhérent et Prestations')
                ->schema([
                    Section::make()->schema(SinistreResource::getFormSchema('prestation')),
                ]),
            Step::make('Détails des actes')
                ->schema([
                    Section::make()->schema([
                        SinistreResource::getItemsRepeater(),
                    ]),
                ]),
        ];
    }
}
