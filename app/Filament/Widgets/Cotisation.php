<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Cotisations;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class Cotisation extends ChartWidget
{
    
    protected static ?string $heading = 'Cotisations mensuelles';
    protected static ?int $sort = 3;

    public Carbon $fromDate;
    public Carbon $toDate;

    #[On('updateFromDate')] 
    public function updateFromDate(string $from): void
    {
        $this->fromDate = Carbon::make($from);
        $this->updateChartData();
    }
 
    #[On('updateToDate')]
    public function updateToDate(string $to): void
    {
        $this->toDate = Carbon::make($to);
        $this->updateChartData();
    } 

    protected function getData(): array
    {
        
        $fromDate = $this->fromDate ??= now()->startOfYear(); //subWeek(); 
        $toDate = $this->toDate ??= now();

        $data = Trend::model(Cotisations::class)
        ->between(
            //start: now()->startOfYear(),
            //end: now()->endOfYear(),

            start: $fromDate, 
            end: $toDate,
        )
        ->perMonth()
        ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Cotisations mensuelles',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
