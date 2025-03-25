<?php

namespace App\Filament\Reports;

use Filament\Forms\Form;
use App\Models\SinistreActe;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\VerticalSpace;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class ActesReport extends Report
{
    public ?string $heading = "Reporting Actes";
    protected ?string $maxContentWidth = 'full';
    protected static ?string $navigationGroup = 'REPORTING';
    protected static ?string $navigationLabel = 'Actes consommés';

    protected static ?int $navigationSort = 1;

    // public ?string $subHeading = "A great report";

    public function header(Header $header): Header
    {
        $imagePath = asset('img/fr-logo.png');
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Image::make($imagePath)
                                    ->width9Xl(),
                            ])->alignLeft(),
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Text::make("DEPENSES PAR ACTE DE PRESTATIONS")
                                    ->title()
                                    ->primary(),
                                Text::make("CONVENTION DE GESTION")
                                    ->subTitle(),
                                Text::make("STATISTIQUES". " " . now()->format("Y"))
                                    ->subtitle(),
                                Text::make("Période du 01/01/2025 au 31/12/2025")
                                    ->subTitle(),
                            ])->alignRight()
                    ]),
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Text::make("Actes consommés")
                            ->fontXl()
                            ->fontBold()
                            ->primary(),
                        Text::make("Liste des actes consommés sur la période spécifiée")
                            ->fontSm()
                            ->secondary(),
                        Body\Table::make()
                            ->columns([
                                Body\TextColumn::make("mntact")
                                    ->label("Montant déclaré"),
                                Body\TextColumn::make("qteact")
                                    ->label("Quantité"),
                                Body\TextColumn::make("mnttot")
                                    ->label("Montant payé"),
                                Body\TextColumn::make("created_at")
                                    ->label("Date")
                                    ->dateTime(),
                            ])
                            ->data(
                                function (?array $filters) {
                                    [$from, $to] = $filters['registration_date'] ?? null; /* getCarbonInstancesFromDateString(
                                        
                                    ); */
                                    //fn(?array $filters) => $this->registrationSummary($filters);
                                    return SinistreActe::query()
                                        ->when($from, function ($query, $date) {
                                            return $query->whereDate('created_at', '>=', $date);
                                        })
                                        ->when($to, function ($query, $date) {
                                            return $query->whereDate('created_at', '<=', $date);
                                        })
                                        ->select("mntact", "qteact", "mnttot", "created_at")
                                        ->take(10)
                                        ->get();
                                }
                            ),
                        //VerticalSpace::make(),
                    ]),
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                Text::make("Généré le: " . now()->format("d/m/Y H:i:s"))
                ->subtitle(),
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
        ->schema([
            DateRangePicker::make("registration_date")
                ->label("Registration date")
                ->placeholder("Select a date range"),
            DateRangePicker::make("verification_date")
                ->label("Verification date")
                ->placeholder("Select a date range"),
        ]);
    }
}
