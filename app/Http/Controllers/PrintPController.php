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
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintPController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Pcharge $pc)
    {
        $fileName = "{$pc->numpch}.pdf";
        
        //Infos dans le qr code
        $inqr = $pc->numpch;

        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($inqr));
        //$qrcode = base64_encode(QrCode::format('png')->mergeString(Storage::get('../../../storage/app/public/LOGO.png'), .3)->generate());
        $data = [
            'qrcode' => $qrcode
        ];

        //Le prestataire
        $prs= Prestataire::find($pc->prestataire_id);
       
        //Famille - bénéficiaire
        $ben= Membre::find($pc->membre_id);
        $fam= Famille::find($pc->famille_id);

        //Listing actes
        $actes = DB::table('pcharge_actes')
                ->where('pcharge_id', '=', $pc->id)
                ->get();

        $som_act = $actes->sum('mntact'); // Somme des actes prescrits
        
        $pdf = PDF::loadView('printpc', compact('pc','prs','ben','fam', 'actes','som_act','qrcode','data'));

        return $pdf->stream($fileName);
    }
}
