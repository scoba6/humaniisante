<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintPController;
use App\Http\Controllers\WhatsAppController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::redirect('/','/admin');

//Impression
Route::get('/printpc/{pc}', PrintPController::class)->name('printpc'); // Prise en charge

//Whatsapp
Route::get('/admin/send-whatsapp', [WhatsAppController::class, 'sendWhatsAppMessage']);
