<?php

namespace App\Filament\Widgets;

use App\Models\Membre;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class Adhesion extends ChartWidget
{
    
    protected static ?string $heading = 'Adhésions mensuelles';
    protected static ?int $sort = 2;

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

    protected static ?array $options = [
        
        'indexAxis'=> 'y',
    ];

    protected function getData(): array
    {
        $fromDate = $this->fromDate ??= now()->startOfYear(); //subWeek();
        $toDate = $this->toDate ??= now();

        $data = Trend::model(Membre::class)
            ->between(
                /* start: now()->startOfYear(),
                end: now()->endOfYear(), */
                start: $fromDate, 
                end: $toDate,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Adhésions mensuelles',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate), 
                    'backgroundColor'=> [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    'borderColor'=> [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                      ],
                      'borderWidth'=> 1
                   
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
