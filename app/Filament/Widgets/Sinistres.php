<?php

namespace App\Filament\Widgets;

use App\Models\Sinistre;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class Sinistres extends ChartWidget
{
    protected static ?string $heading = 'Suivi de la sinistralité';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';

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

        $data = Trend::model(Sinistre::class)
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
                    'label' => 'Evolutions du nombre de sinistres',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
