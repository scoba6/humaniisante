<?php

namespace App\Filament\Widgets;

use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Carbon;

class DateFilter extends Widget implements HasForms
{
    
    use InteractsWithForms;
 
    protected static string $view = 'filament.widgets.date-filter';
 
    
 
    protected static ?int $sort = -2;
 
    public ?array $data = [];
 
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        DatePicker::make('DU')
                            ->maxDate(now())
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->native(false)
                            ->live() 
                            ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateFromDate', from: $state)),
                        DatePicker::make('AU')
                            ->maxDate(now())
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->native(false)
                            ->live() 
                            ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateToDate', to: $state)), 
                    ]),
            ]);
    }
}
