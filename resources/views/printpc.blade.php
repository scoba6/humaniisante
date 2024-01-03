<?php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>PCHARGE</title>
		<link rel="stylesheet" href="../../public/">
		<link rel="license" href="https://www.opensource.org/licenses/mit-license/">

    <style>

        body {
                    margin: 1px;
                    padding: 1px;
                    font-family: sans-serif;
                }
 

        h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }

        /* table */

        table { font-size: 75%; table-layout: fixed; width: 100%; }
        table { border-collapse: separate; border-spacing: 2px; }
        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
        th, td { border-radius: 0.25em; border-style: solid; }
        th { background: #EEE; border-color: #BBB; }
        td { border-color: #DDD; }

        /* page */

        html { font: 16px/1 'Open Sans', sans-serif; overflow: auto; padding: 0.5in; }
        html { background: #999; cursor: default; }

        /* header */

        header { margin: 0 0 3em; }
        header:after { clear: both; content: ""; display: table; }

        header h1 { background: #000; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
        header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
        header address p { margin: 0 0 0.25em; }
        header span, header img { display: block; float: right; }
        header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
        header img { max-height: 100%; max-width: 100%; }
        header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }

        /* article */

        article, article address, table.meta, table.inventory { margin: 0 0 3em; }
        article:after { clear: both; content: ""; display: table; }
        article h1 { clip: rect(0 0 0 0); position: absolute; }

        article address { float: left; font-size: 125%; font-weight: bold; }

        /* table meta & balance */

        table.meta, table.balance { float: right; width: 36%; }
        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

        /* table meta */

        table.meta th { width: 40%; }
        table.meta td { width: 60%; }

        /* table items */

        table.inventory { clear: both; width: 100%; }
        table.inventory th { font-weight: bold; text-align: center; }

        table.inventory td:nth-child(1) { width: 26%; }
        table.inventory td:nth-child(2) { width: 38%; }
        table.inventory td:nth-child(3) { text-align: right; width: 12%; }
        table.inventory td:nth-child(4) { text-align: right; width: 12%; }
        table.inventory td:nth-child(5) { text-align: right; width: 12%; }

        /* table balance */
        table.balance th, table.balance td { width: 50%; }
        table.balance td { text-align: right; }

        /*table signature */
        table.signature th {      
            border: 0px ;
            border-collapse: separate;
            text-align: center;
        }
  

        /* aside */
        aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
        aside h1 { border-color: #999; border-bottom-style: solid; }



    </style>

	</head>
	<body>
		<header>
			<h1>PRISE EN CHARGE</h1>
			<address >
				<p>{{$prs->rsopre}}<br>{{$prs->adrpre}}</p>
				<p>{{$prs->telpre}}</p>
			</address>
            <span>
            
           
            </span>
		
    
        </header>
		<article>
			<h1>BENEFICIAIRE:</h1>
            <br>
			<address >
				<p>{{$ben->nommem}}<br> P/C {{$fam->nomfam}}</p>
                <p>{{$fam->numcdg}}</p>
			</address>
			<table class="meta">
				<tr>
					<th><span >#</span></th>
					<td><span >{{$pc->numpch}}</span></td>
				</tr>
				<tr>
					<th><span >Emission</span></th>
					<td><span >{{\Carbon\Carbon::parse($pc->datemi)->format('d/m/Y')}}</span></td>
				</tr>
                <tr>
					<th><span >Echeance</span></th>
					<td><span >{{\Carbon\Carbon::parse($pc->datexp)->format('d/m/Y')}}</span></td>
				</tr>
			</table>
			<table class="inventory">
				<thead>
					<tr>
						<th><span >ACTE</span></th>
						<th><span >CLASSEMENT</span></th>
						<th><span >QUANTITE</span></th>
						<th><span >PU PLAFOND</span></th>
					</tr>
				</thead>
				<tbody>
                    @foreach($actes as $indexKey => $acte)
                        <tr>
                            <td><span>{{App\Models\Acte::find($acte->acte_id)->libact}}</span></td>
                            <td><span>Classement</span></td>
                            <td><span>{{$acte->qteact}}</span></td>
                            <td><span>{{$acte->mntact}}</span></td>
                        </tr>
                    @endforeach
				</tbody>
			</table>
		
			<table class="balance">
				<tr>
					<th><span >Total</span></th>
					<td><span data-prefix>$</span><span>600.00</span></td>
				</tr>
				
			</table>
		</article>
		<aside>
			<h1><span >LIMITATIONS</span></h1>
			<div>
				<p>Prise en chage à concurrence de FCFA, selon les plafonds du contrat. Dossier sous réserve des droits
                    de l'assuré à la date réelle des soins et conformément au barème, aux plafonds, aux exclusions et aux limites prévus par
                    le contrat.
                </p>
			</div>
		</aside>
    <br>
         <aside>
			<h1><span >INSTRUCTIONS PARTICULIERES</span></h1>
			<div>
				<p>Exemplaire à signer et à retourner à l'assureur accompagné des pièces ci-après:</p>
                <ol type="1">
                    <li>Rapport médical</li>
                    <li>Ordonnances originales et pièces détaillées des pharmacies en cas d'achat de médicaments</li>
                    <li>Facture du prestataire</li>
                </ol> 
			</div>
		</aside>

    
        <table class='signature' style="width:100%">
            <tr>
                <th>LE BENEFICIAIRE</th>
                <th>LE PRESTATAIRE</th>
                <th>POUR LA COMPAGNIE</th>
            </tr>
        </table>	
	</body>
    
</html>