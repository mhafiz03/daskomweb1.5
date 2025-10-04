<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// PUBLIC WEB ROUTE (for android app development purpose) & that is why its called api :v
Route::post('/getTp/{praktikan_nim}/{modul_id}', [\App\Http\Controllers\JawabanTpController::class, 'show'])->name('getTp');
Route::post('/getAllLaporan/{modul_id}', [\App\Http\Controllers\LaporanPjController::class, 'index'])->name('getAllLaporan');
Route::post('/getAllModul', [\App\Http\Controllers\ModulController::class, 'show'])->name('getAllModul');
// PUBLIC WEB ROUTE FOR NILAI (for laboran purposes) 
Route::post('/getNilai/{praktikan_nim}/{modul_id}',[\App\Http\Controllers\NilaiController::class, 'showApi'])->name('getNilai');
