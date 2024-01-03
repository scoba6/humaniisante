<?php

namespace App\Http\Controllers;

//use Barryvdh\DomPDF\PDF;

use App\Models\Membre;
use App\Models\Famille;
use App\Models\Pcharge;
use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintPController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Pcharge $pc)
    {
        $fileName = "{$pc->numpch}.pdf";

        //$qrcode = QrCode::generate('Transformez-moi en QrCode !');
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('string'));
        //Le prestataire
        $prs= Prestataire::find($pc->prestataire_id);
       
        //Famille - bénéficiaire
        $ben= Membre::find($pc->membre_id);
        $fam= Famille::find($pc->famille_id);

        //Listing actes
        $actes = DB::table('pcharge_actes')
                ->where('pcharge_id', '=', $pc->id)
                ->get();
        

        

        $pdf = PDF::loadView('printpc', compact('pc','prs','ben','fam', 'actes','qrcode'));

        return $pdf->stream($fileName);
    }
}
