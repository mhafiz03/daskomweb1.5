<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Role;
use App\Models\Kelas;
use App\Models\Feedback;
use App\Models\Modul;
use App\Models\JadwalJaga;
use App\Models\Asisten;
use App\Models\Configuration;
use App\Models\Tugaspendahuluan;
use App\Models\LaporanPraktikan;
use App\Models\Nilai;
use App\Models\Praktikan;
use App\Models\Polling;
use App\Models\JenisPolling;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    return Inertia::render('Welcome', [
        'comingFrom' => $comingFrom
    ]);
})->middleware('guest');

Route::get('/about', function () {
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    return Inertia::render('About', [
        'comingFrom' => $comingFrom
    ]);
})->middleware('guest');

Route::get('/contact', function () {
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    return Inertia::render('Contact', [
        'comingFrom' => $comingFrom
    ]);
})->middleware('guest');

Route::get('/login', function () {
    $all_kelas = Kelas::where('id','!=',12)->orderBy('kelas','asc')->get();
    $roles = Role::all();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    return Inertia::render('Login', [
        'comingFrom' => $comingFrom,
        'all_kelas' => $all_kelas,
        'roles' => $roles
    ]);
})->name('login')->middleware('guest');

Route::get('/asisten', function () {
    $user = Auth::guard('asisten')->user();
    $messages = Feedback::where('asisten_id', $user->id)
                    ->leftJoin('kelas', 'feedback.kelas_id', '=', 'kelas.id')
                    ->leftJoin('praktikans', 'feedback.praktikan_id', '=', 'praktikans.id')
                    ->orderBy('feedback.created_at', 'desc')->get();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    return Inertia::render('Asisten', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'messages' => $messages,
        'userRole' => $userRole->role,
    ]);
})->name('asisten')->middleware('loggedIn:asisten');

Route::get('/praktikan', function () {
    $user = Auth::guard('praktikan')->user();
    $user->kelas = Kelas::where('id', $user->kelas_id)->first()->kelas;
    $allAsisten = Asisten::orderBy('kode','asc')->get();
    $allAsistenPolling = Asisten::where('kode','!=','BOT')->orderBy('kode','asc')->get();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $isRunmod = Configuration::find(1)->runmod_activation;
    $pollingComplete = Polling::where('praktikan_id', $user->id)->exists();
    $allPolling = JenisPolling::all();
    $allModul = Modul::orderBy('isEnglish','asc')->get();
    $allJurnal = DB::table('soal__jurnals')
            ->join('moduls', 'soal__jurnals.modul_id', '=', 'moduls.id')
            ->select('soal__jurnals.*', 'moduls.judul')->get();
    return Inertia::render('Praktikan', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'allAsisten' => $allAsisten,
        'allAsistenPolling' => $allAsistenPolling,
        'isRunmod' => $isRunmod,
        'pollingComplete' => $pollingComplete,
        'allPolling' => $allPolling,
        'allModul' => $allModul,
        'allJurnal' => $allJurnal,
    ]);
})->name('praktikan')->middleware('loggedIn:praktikan');

Route::get('/soal', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $allModul = Modul::orderBy('isEnglish','asc')->get();
    $allTP = DB::table('soal__tps')
            ->join('moduls', 'soal__tps.modul_id', '=', 'moduls.id')
            ->select('soal__tps.*', 'moduls.judul')->get();
    $allTA = DB::table('soal__tas')
            ->join('moduls', 'soal__tas.modul_id', '=', 'moduls.id')
            ->select('soal__tas.*', 'moduls.judul')->get();
    $allTK = DB::table('soal__tks')
            ->join('moduls', 'soal__tks.modul_id', '=', 'moduls.id')
            ->select('soal__tks.*', 'moduls.judul')->get();
    $allJurnal = DB::table('soal__jurnals')
            ->join('moduls', 'soal__jurnals.modul_id', '=', 'moduls.id')
            ->select('soal__jurnals.*', 'moduls.judul')->get();
    $allMandiri = DB::table('soal__mandiris')
            ->join('moduls', 'soal__mandiris.modul_id', '=', 'moduls.id')
            ->select('soal__mandiris.*', 'moduls.judul')->get();
    $allFITB = DB::table('soal__fitbs')
            ->join('moduls', 'soal__fitbs.modul_id', '=', 'moduls.id')
            ->select('soal__fitbs.*', 'moduls.judul')->get();

    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    return Inertia::render('Soal', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allModul' => $allModul,

        'allTP' => $allTP,
        'allTA' => $allTA,
        'allTK' => $allTK,
        'allJurnal' => $allJurnal,
        'allMandiri' => $allMandiri,
        'allFITB' => $allFITB,
    ]);
})->name('soal')->middleware('loggedIn:asisten');

Route::get('/kelas', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $allKelas = Kelas::all();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $kelasPrivilege = array(1,2,4,5);
    if(in_array($userRole->id,$kelasPrivilege,true)){
        return Inertia::render('Kelas', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'allKelas' => $allKelas,
        ]);
    }else return redirect('/');
})->name('kelas')->middleware('loggedIn:asisten');

Route::get('/modul', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $allModul = Modul::orderBy('isEnglish','asc')->get();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $modulPrivilege = array(1,2,4,15,7);
    if(in_array($userRole->id,$modulPrivilege,true)){
        return Inertia::render('Modul', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
        ]);
    }else return redirect('/');
})->name('modul')->middleware('loggedIn:asisten');

Route::get('/plotting', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $allJaga = DB::table('jadwal__jagas')
            ->join('asistens', 'jadwal__jagas.asisten_id', '=', 'asistens.id')
            ->join('kelas', 'jadwal__jagas.kelas_id', '=', 'kelas.id')
            ->select('jadwal__jagas.*', 'asistens.kode', 'kelas.kelas', 'kelas.hari', 'kelas.shift')->get();
    $allKelas = Kelas::all();
    $allAsisten = Asisten::orderBy('kode','asc')->get();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $plottingPrivilege = array(1,2,4,5);
    if(in_array($userRole->id,$plottingPrivilege,true)){
        return Inertia::render('Plotting', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'allJaga' => $allJaga,
            'allKelas' => $allKelas,
            'allAsisten' => $allAsisten,
        ]);
    }else return redirect('/');
})->name('plotting')->middleware('loggedIn:asisten');

Route::get('/praktikum', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    
    $allKelas = Kelas::all();
    $allModul = Modul::orderBy('isEnglish','asc')->get();
    $isRunmod = Configuration::find(1)->runmod_activation;
    return Inertia::render('Praktikum', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allKelas' => $allKelas,
        'allModul' => $allModul,
        'isRunmod' => $isRunmod,
    ]);
})->name('praktikum')->middleware('loggedIn:asisten');

Route::get('/konfigurasi', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    
    $currentConfig = Configuration::find(1); // Always get the first configuration
    $konfigurasiPrivilege = array(1,2,4,18,7);
    if(in_array($userRole->id,$konfigurasiPrivilege,true)){
        return Inertia::render('Konfigurasi', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'currentConfig' => $currentConfig === null ? 'nope' : $currentConfig,
        ]);
    }else return redirect('/');
})->name('konfigurasi')->middleware('loggedIn:asisten');

Route::get('/jawaban', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    
    $allModul = Modul::where('isEnglish', 0)
        ->where('id', '<', '20')
        ->orderBy('id','asc')
        ->get();
        
    $jawabanPrivilege = array(1,2,7,11,15);
    if(in_array($userRole->id,$jawabanPrivilege,true)){
        return Inertia::render('Jawaban', [
            'comingFrom'    => $comingFrom,
            'currentUser'   => $user,
            'position'      => $position,
            'userRole'      => $userRole->role,
            'allModul'      => $allModul,
        ]);
    }else return redirect('/');
})->name('jawaban')->middleware('loggedIn:asisten');

Route::get('/pelanggaran', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');

    $allAsisten = Asisten::orderBy('kode','asc')->get();
    foreach ($allAsisten as $asisten => $asisten_val) {

        $allLaporan = LaporanPraktikan::where('laporan__praktikans.asisten_id',    $asisten_val->id)
            ->join('praktikans', 'laporan__praktikans.praktikan_id', '=', 'praktikans.id')
            ->select('laporan__praktikans.*', 'praktikans.nama', 'praktikans.nim', 'praktikans.kelas_id')
            ->latest()
            ->get();

        $nilaiUnexists = 0;
        foreach ($allLaporan as $laporan => $value)
            if(Nilai::where('praktikan_id', $value->praktikan_id)
                ->where('modul_id', $value->modul_id)
                ->where('asisten_id', $value->asisten_id)
                ->exists()) {

                $value->nilaiExists = true;
            } else {

                $nilaiUnexists++; 
                $value->nilaiExists = false;
            }

        $asisten_val->nilaiUnexists = $nilaiUnexists;
    }
    $pelanggaranPrivilege = array(1,2,4,5,6,18);
    if(in_array($userRole->id,$pelanggaranPrivilege,true)){
        return Inertia::render('Pelanggaran', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'allAsisten' => $allAsisten,
        ]);
    }else return redirect('/');
})->name('pelanggaran')->middleware('loggedIn:asisten');

Route::get('/polling', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $jenisPollings = JenisPolling::all();

    $allAsisten = Asisten::all();

    $pollingResults = array();
    foreach ($allAsisten as $each_asisten => $asisten) {

        $allPolling = array();
        foreach ($jenisPollings as $each_jenis => $jenis) {
            
            $jumlahPoll = Polling::where('polling_id', $jenis->id)
                ->where('asisten_id', $asisten->id)
                ->count();
             
            $allPolling[] = (object)
                ['jenis' => $jenis->judul,
                 'jumlah_poll' => $jumlahPoll];
        }

        $pollingResults[] = (object) 
            ['id' => $asisten->id,
             'kode' => $asisten->kode,
             'nama' => $asisten->nama,
             'polling' => $allPolling];
    }

    return Inertia::render('Polling', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allJenisPollings' => $jenisPollings, 
        'allPollingResults' => $pollingResults,
    ]);
})->name('polling')->middleware('loggedIn:asisten');

Route::get('/tp', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $allTP = DB::table('tugaspendahuluans')
        ->join('moduls', 'tugaspendahuluans.modul_id', '=', 'moduls.id')
        ->select('tugaspendahuluans.*', 'moduls.judul', 'moduls.isEnglish')->get();
    $allModul = Modul::orderBy('isEnglish','asc')->get();

    if($allTP !== null){
        foreach ($allTP as $key => $value) {
            if($value->isActive == '1')
                $value->isActive = true;
            else 
                $value->isActive = false;
        }
    }
    $tpPrivilege = array(1,2,15,11,7);
    if(in_array($userRole->id,$tpPrivilege,true)){
        return Inertia::render('TugasPendahuluan', [
            'comingFrom' => $comingFrom,
            'currentUser' => $user,
            'position' => $position,
            'userRole' => $userRole->role,
            'allTP' => $allTP === null ? 'nope' : $allTP,
            'allModul' => $allModul,
        ]);
    }else return redirect('/');
})->name('tp')->middleware('loggedIn:asisten');

# DISABLE THIS ROUTE
// Route::get('/listTp', function () {
//     $user = Auth::guard('asisten')->user();
//     $userRole = Role::where('id', $user->role_id)->first();
//     $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
//     $position = request('position') === null ? 0:request('position');
//     $allKelas = Kelas::all();
//     $allModul = Modul::all();

//     return Inertia::render('ListTp', [
//         'comingFrom' => $comingFrom,
//         'currentUser' => $user,
//         'position' => $position,
//         'userRole' => $userRole->role,
//         'allKelas' => $allKelas,
//         'allModul' => $allModul,
//     ]);
// })->name('listTp')->middleware('loggedIn:asisten');

Route::get('/nilai', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $allLaporan = LaporanPraktikan::where('laporan__praktikans.asisten_id', $user->id)
        ->join('praktikans', 'laporan__praktikans.praktikan_id', '=', 'praktikans.id')
        ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
        ->select('laporan__praktikans.*', 'praktikans.nama', 'praktikans.nim', 'praktikans.kelas_id', 'kelas', 'kelas.shift', 'kelas.hari')
        ->latest()
        ->get();
    foreach ($allLaporan as $laporan => $value)
        if(Nilai::where('praktikan_id', $value->praktikan_id)
            ->where('modul_id', $value->modul_id)
            ->where('asisten_id', $value->asisten_id)
            ->exists())
            $value->nilaiExists = true;
        else 
            $value->nilaiExists = false;

    return Inertia::render('Nilai', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allLaporan' => $allLaporan === null ? [] : $allLaporan,
    ]);
})->name('nilai')->middleware('loggedIn:asisten');

Route::get('/history', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    
    $asistenExist = false;
    $allAsistenHistory = [];
    $allHistory = DB::table('laporan__pjs')
        ->join('moduls', 'laporan__pjs.modul_id', '=', 'moduls.id')
        ->select('laporan__pjs.*', 'moduls.judul')->orderBy('created_at','desc')->get();
    foreach ($allHistory as $history => $h) {

        $asistenExist = false;
        foreach (explode('-', $h->allasisten_id) as $asisten => $a)
            if($a == $user->id)
                $asistenExist = true;

        if($asistenExist){
            array_push($allAsistenHistory, $h);
        }
    }

    return Inertia::render('History', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allHistory' => $allAsistenHistory === null ? 'nope' : $allAsistenHistory,
    ]);
})->name('history')->middleware('loggedIn:asisten');

Route::get('/setpraktikan', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $allModul = Modul::orderBy('isEnglish','asc')->get();

    return Inertia::render('SetPraktikan', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allModul' => $allModul,
    ]);
})->name('setpraktikan')->middleware('loggedIn:asisten');

Route::get('/lihat_tp', function () {
    $user = Auth::guard('asisten')->user();
    $allModul = Modul::orderBy('isEnglish','asc')->get();

    return Inertia::render('Lihat_Tp', [
        'currentUser' => $user,
        'allModul' => $allModul,
    ]);
})->name('lihat_tp');

Route::get('/logoutAsisten', [\App\Http\Controllers\Auth\AsistenLoginController::class, 'logout'])->name('logoutAsisten');
Route::get('/logoutPraktikan', [\App\Http\Controllers\Auth\PraktikanLoginController::class, 'logout'])->name('logoutAsisten');

Route::post('/signupAsisten', [\App\Http\Controllers\AsistenController::class, 'store'])->name('signupAsisten');
Route::post('/signupPraktikan', [\App\Http\Controllers\PraktikanController::class, 'store'])->name('signupPraktikan');
Route::post('/loginPraktikan', [\App\Http\Controllers\Auth\PraktikanLoginController::class, 'login'])->name('loginPraktikan');
Route::post('/loginAsisten', [\App\Http\Controllers\Auth\AsistenLoginController::class, 'login'])->name('loginAsisten');

Route::post('/sendPesan', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('sendPesan')->middleware('loggedIn:praktikan');
Route::post('/readPesan', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('readPesan')->middleware('loggedIn:asisten');

Route::post('/createKelas', [\App\Http\Controllers\KelasController::class, 'store'])->name('createPesan')->middleware('loggedIn:asisten');
Route::post('/deleteKelas', [\App\Http\Controllers\KelasController::class, 'destroy'])->name('deletePesan')->middleware('loggedIn:asisten');
Route::post('/updateKelas', [\App\Http\Controllers\KelasController::class, 'update'])->name('updatePesan')->middleware('loggedIn:asisten');

Route::post('/createModul', [\App\Http\Controllers\ModulController::class, 'store'])->name('createModul')->middleware('loggedIn:asisten');
Route::post('/deleteModul/{id}', [\App\Http\Controllers\ModulController::class, 'destroy'])->name('deleteModul')->middleware('loggedIn:asisten');
Route::post('/updateModul', [\App\Http\Controllers\ModulController::class, 'update'])->name('updateModul')->middleware('loggedIn:asisten');
Route::post('/readModul', [\App\Http\Controllers\ModulController::class, 'show'])->name('readModul')->middleware('loggedIn:asisten');
Route::post('/getModul/{id}', [\App\Http\Controllers\ModulController::class, 'index'])->name('getModul')->middleware('loggedIn:all');

Route::post('/createTP', [\App\Http\Controllers\SoalTpController::class, 'store'])->name('createTP')->middleware('loggedIn:asisten');
Route::post('/deleteTP/{id}', [\App\Http\Controllers\SoalTpController::class, 'destroy'])->name('deleteTP')->middleware('loggedIn:asisten');
Route::post('/updateTP', [\App\Http\Controllers\SoalTpController::class, 'update'])->name('updateTP')->middleware('loggedIn:asisten');

Route::post('/createTA', [\App\Http\Controllers\SoalTaController::class, 'store'])->name('createTA')->middleware('loggedIn:asisten');
Route::post('/deleteTA/{id}', [\App\Http\Controllers\SoalTaController::class, 'destroy'])->name('deleteTA')->middleware('loggedIn:asisten');
Route::post('/updateTA', [\App\Http\Controllers\SoalTaController::class, 'update'])->name('updateTA')->middleware('loggedIn:asisten');

Route::post('/createTK', [\App\Http\Controllers\SoalTkController::class, 'store'])->name('createTK')->middleware('loggedIn:asisten');
Route::post('/deleteTK/{id}', [\App\Http\Controllers\SoalTkController::class, 'destroy'])->name('deleteTK')->middleware('loggedIn:asisten');
Route::post('/updateTK', [\App\Http\Controllers\SoalTkController::class, 'update'])->name('updateTK')->middleware('loggedIn:asisten');

Route::post('/createJurnal', [\App\Http\Controllers\SoalJurnalController::class, 'store'])->name('createJurnal')->middleware('loggedIn:asisten');
Route::post('/deleteJurnal/{id}', [\App\Http\Controllers\SoalJurnalController::class, 'destroy'])->name('deleteJurnal')->middleware('loggedIn:asisten');
Route::post('/updateJurnal', [\App\Http\Controllers\SoalJurnalController::class, 'update'])->name('updateJurnal')->middleware('loggedIn:asisten');

Route::post('/createMandiri', [\App\Http\Controllers\SoalMandiriController::class, 'store'])->name('createMandiri')->middleware('loggedIn:asisten');
Route::post('/deleteMandiri/{id}', [\App\Http\Controllers\SoalMandiriController::class, 'destroy'])->name('deleteMandiri')->middleware('loggedIn:asisten');
Route::post('/updateMandiri', [\App\Http\Controllers\SoalMandiriController::class, 'update'])->name('updateMandiri')->middleware('loggedIn:asisten');

Route::post('/createFitb', [\App\Http\Controllers\SoalFitbController::class, 'store'])->name('createFitb')->middleware('loggedIn:asisten');
Route::post('/deleteFitb/{id}', [\App\Http\Controllers\SoalFitbController::class, 'destroy'])->name('deleteFitb')->middleware('loggedIn:asisten');
Route::post('/updateFitb', [\App\Http\Controllers\SoalFitbController::class, 'update'])->name('updateFitb')->middleware('loggedIn:asisten');

Route::post('/createJadwalJaga', [\App\Http\Controllers\JadwalJagaController::class, 'store'])->name('createJadwalJaga')->middleware('loggedIn:asisten');
Route::post('/deleteJadwalJaga', [\App\Http\Controllers\JadwalJagaController::class, 'delete'])->name('deleteJadwalJaga')->middleware('loggedIn:asisten');
Route::post('/resetJadwalJaga', [\App\Http\Controllers\JadwalJagaController::class, 'destroy'])->name('resetJadwalJaga')->middleware('loggedIn:asisten');

Route::post('/readDataKelas/{kelas_id}', [\App\Http\Controllers\KelasController::class, 'show'])->name('updatePesan')->middleware('loggedIn:asisten');
Route::post('/cekPraktikum', [\App\Http\Controllers\PraktikumController::class, 'index'])->name('cekPraktikum')->middleware('loggedIn:asisten');

Route::post('/createLaporanPJ', [\App\Http\Controllers\LaporanPjController::class, 'store'])->name('createLaporanPJ')->middleware('loggedIn:asisten');
Route::post('/deleteLaporanPJ/{id}', [\App\Http\Controllers\LaporanPjController::class, 'destroy'])->name('deleteLaporanPJ')->middleware('loggedIn:asisten');
Route::post('/updateLaporanPJ', [\App\Http\Controllers\LaporanPjController::class, 'update'])->name('updateLaporanPJ')->middleware('loggedIn:asisten');
Route::post('/currentLaporanPJ', [\App\Http\Controllers\LaporanPjController::class, 'show'])->name('currentLaporanPJ')->middleware('loggedIn:asisten');

Route::post('/startPraktikum', [\App\Http\Controllers\CurrentPraktikumController::class, 'store'])->name('startPraktikum')->middleware('loggedIn:asisten');
Route::post('/continuePraktikum/{status}', [\App\Http\Controllers\CurrentPraktikumController::class, 'update'])->name('continuePraktikum')->middleware('loggedIn:asisten');
Route::post('/stopPraktikum', [\App\Http\Controllers\CurrentPraktikumController::class, 'destroy'])->name('stopPraktikum')->middleware('loggedIn:asisten');
Route::post('/checkPraktikum', [\App\Http\Controllers\CurrentPraktikumController::class, 'show'])->name('checkPraktikum')->middleware('loggedIn:all');

Route::post('/makeHistory/jaga', [\App\Http\Controllers\HistoryJagaController::class, 'store'])->name('createJagaHistory')->middleware('loggedIn:asisten');
Route::post('/deleteHistory/jaga', [\App\Http\Controllers\HistoryJagaController::class, 'destroy'])->name('deleteJagaHistory')->middleware('loggedIn:asisten');
Route::post('/latestPJHistory/jaga', [\App\Http\Controllers\HistoryJagaController::class, 'show'])->name('latestPJHistory')->middleware('loggedIn:asisten');

Route::post('/makeHistory/izin', [\App\Http\Controllers\HistoryIzinController::class, 'store'])->name('createIzinHistory')->middleware('loggedIn:asisten');

Route::post('/createPraktikum', [\App\Http\Controllers\PraktikumController::class, 'store'])->name('createPraktikum')->middleware('loggedIn:asisten');

// TODO: Secure this 'getSoal' route from others by adding some private key algorithm to the request
Route::get('/getSoalTP/{isEnglish}/{praktikan_id}', [\App\Http\Controllers\SoalTpController::class, 'show'])->name('getSoalTP');
Route::get('/getSoalTA/{modul_id}/{kelas_id}', [\App\Http\Controllers\SoalTaController::class, 'show'])->name('getSoalTA');
Route::get('/getSoalTK/{modul_id}/{kelas_id}', [\App\Http\Controllers\SoalTkController::class, 'show'])->name('getSoalTK');
Route::get('/getSoalFITB', [\App\Http\Controllers\SoalFitbController::class, 'show'])->name('getSoalFITB');
Route::get('/getSoalJURNAL', [\App\Http\Controllers\SoalJurnalController::class, 'show'])->name('getSoalJURNAL');
Route::get('/getSoalRUNMOD', [\App\Http\Controllers\SoalJurnalController::class, 'showRunmod'])->name('getSoalRUNMOD');
Route::get('/getSoalMANDIRI/{modul_id}/{kelas_id}', [\App\Http\Controllers\SoalMandiriController::class, 'show'])->name('getSoalMANDIRI');
////////////////////////////////////////////////////////////////////////////////////////////////////

Route::post('/sendLaporan', [\App\Http\Controllers\LaporanPraktikanController::class, 'store'])->name('sendLaporan')->middleware('loggedIn:praktikan');
Route::post('/getLaporan/{praktikan_id}/{modul_id}', [\App\Http\Controllers\LaporanPraktikanController::class, 'show'])->name('getLaporan')->middleware('loggedIn:praktikan');

Route::post('/sendJawabanTA', [\App\Http\Controllers\JawabanTaController::class, 'store'])->name('sendJawabanTA')->middleware('loggedIn:praktikan');
Route::post('/sendJawabanTK', [\App\Http\Controllers\JawabanTkController::class, 'store'])->name('sendJawabanTK')->middleware('loggedIn:praktikan');
Route::post('/sendJawabanJurnal', [\App\Http\Controllers\JawabanJurnalController::class, 'store'])->name('sendJawabanJurnal')->middleware('loggedIn:praktikan');
Route::post('/sendJawabanFitb', [\App\Http\Controllers\JawabanFitbController::class, 'store'])->name('sendJawabanFitb')->middleware('loggedIn:praktikan');
Route::post('/sendJawabanMandiri', [\App\Http\Controllers\JawabanMandiriController::class, 'store'])->name('sendJawabanMandiri')->middleware('loggedIn:praktikan');

Route::post('/deletePraktikanAlfa', [\App\Http\Controllers\PraktikanController::class, 'destroy'])->name('deletePraktikanAlfa')->middleware('loggedIn:asisten');
Route::get('/getProfilAsisten/{asisten_id}', [\App\Http\Controllers\AsistenController::class, 'show'])->name('getProfilAsisten')->middleware('loggedIn:asisten');

Route::post('/saveConfiguration', [\App\Http\Controllers\ConfigurationController::class, 'store'])->name('saveConfiguration')->middleware('loggedIn:asisten');

Route::post('/addPembahasanTP', [\App\Http\Controllers\TugaspendahuluanController::class, 'store'])->name('addPembahasanTP')->middleware('loggedIn:asisten');
Route::post('/getPembahasanTP/{isEnglish}', [\App\Http\Controllers\TugaspendahuluanController::class, 'index'])->name('getPembahasanTP')->middleware('loggedIn:all');
Route::post('/activateTP/{modul_id}', [\App\Http\Controllers\TugaspendahuluanController::class, 'show'])->name('activateTP')->middleware('loggedIn:asisten');
Route::post('/deactivateTP/{modul_id}', [\App\Http\Controllers\TugaspendahuluanController::class, 'destroy'])->name('activateTP')->middleware('loggedIn:asisten');

Route::post('/sendTempJawabanTP', [\App\Http\Controllers\TempJawabantpController::class, 'store'])->name('sendTempJawabanTP')->middleware('loggedIn:praktikan');
Route::post('/saveJawabanTP', [\App\Http\Controllers\KumpulTpController::class, 'save'])->name('saveTp')->middleware('loggedIn:praktikan');
Route::post('/kumpulTp', [\App\Http\Controllers\KumpulTpController::class, 'store'])->name('kumpulTp')->middleware('loggedIn:asisten');
Route::post('/getKumpulTp/{kelas_id}/{modul_id}', [\App\Http\Controllers\KumpulTpController::class, 'show'])->name('getKumpulTp')->middleware('loggedIn:asisten');

Route::post('/createFormNilai/{praktikan_id}/{modul_id}', [\App\Http\Controllers\NilaiController::class, 'index'])->name('createFormNilai')->middleware('loggedIn:asisten');
Route::post('/getAllJawaban/{praktikan_id}/{modul_id}', [\App\Http\Controllers\NilaiController::class, 'list'])->name('getAllJawaban')->middleware('loggedIn:asisten');
Route::post('/inputNilai', [\App\Http\Controllers\NilaiController::class, 'store'])->name('inputNilai')->middleware('loggedIn:asisten');
Route::post('/getCurrentNilai/{praktikan_id}/{modul_id}', [\App\Http\Controllers\NilaiController::class, 'show'])->name('getCurrentNilai')->middleware('loggedIn:asisten');

Route::post('/getAllNilai/{praktikan_id}', [\App\Http\Controllers\NilaiController::class, 'showAll'])->name('getAllNilai')->middleware('loggedIn:praktikan');
Route::post('/praktikanGetJurnal/{praktikan_id}/{modul_id}', \App\Http\Controllers\PraktikanLihatJawabanController::class)->name('praktikanGetJurnal')->middleware('loggedIn:praktikan');

Route::post('/setThisPraktikan/{praktikan_nim}/{asisten_id}/{modul_id}', [\App\Http\Controllers\NilaiController::class, 'edit'])->name('setThisPraktikan')->middleware('loggedIn:asisten');
Route::post('/changePraktikanPass/{praktikan_nim}/{new_pass}', [\App\Http\Controllers\PraktikanController::class, 'edit'])->name('changePraktikanPass')->middleware('loggedIn:asisten');

Route::post('/checkPolling', [\App\Http\Controllers\ConfigurationController::class, 'isPollingEnabled'])->name('checkPolling')->middleware('loggedIn:praktikan');
Route::post('/savePolling', [\App\Http\Controllers\PollingController::class, 'store'])->name('savePolling')->middleware('loggedIn:praktikan');

Route::post('/activateJawaban', [\App\Http\Controllers\ModulController::class, 'updateJawabanConfiguration'])->name('activateJawaban')->middleware('loggedIn:asisten');

/////////////////////////////////////////////FILE UPLOAD////////////////////////////////////////////
Route::get('/upload',[\App\Http\Controllers\UploadController::class, 'upload'])->middleware('loggedIn:asisten');
Route::post('/upload/proses',[\App\Http\Controllers\UploadController::class, 'proses_upload'])->middleware('loggedIn:asisten');

Route::get('/rating', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $allPraktikan = Praktikan::join('kelas','praktikans.kelas_id','=','kelas.id')
                    ->select('praktikans.id','praktikans.nama','praktikans.nim','kelas.kelas','praktikans.kelas_id')
                    ->where('kelas.id','!=',12)
                    ->get();
    $allKelas = Kelas::where('kelas.id','!=',12)->orderBy('kelas','asc')->get();
    foreach ($allPraktikan as $praktikan => $prak_value) {
        $allLaporan = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('rating');
                        $allTP = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('tp');
        $allTA = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('ta');
        $allJurnal = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('jurnal');
        $allTK = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('tk');
        $allSkill = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('skill');
        $allDiskon = Nilai::where('nilais.praktikan_id',$prak_value->id)
                        ->join('praktikans','nilais.praktikan_id','=','praktikans.id')
                        ->join('kelas','praktikans.kelas_id','=','kelas.id')
                        ->select('nilais.rating')
                        ->sum('diskon');
        $prak_value->tp = $allTP;
        $prak_value->ta = $allTA;
        $prak_value->jurnal = $allJurnal;
        $prak_value->tk = $allTK;
        $prak_value->diskon = $allDiskon;
        $prak_value->total = ($allTP*0.15)+($allTA*0.15)+($allJurnal*0.1)+($allTK*0.2)-($allDiskon*0.01);
        $prak_value->rating = $allLaporan;  
    }

    $allRating = array();

    foreach ($allKelas as $key => $kelas) {
        $allPraktikanArr = array();
        foreach ($allPraktikan as $key2 => $praktikan) {
            if($praktikan->kelas_id==$kelas->id){
                array_push($allPraktikanArr, $praktikan);
            }
        }
        $allPraktikanArr = collect($allPraktikanArr);
        $allPraktikanArr = $allPraktikanArr->sortByDesc('rating');
        $tempPraktikan = array();
        foreach ($allPraktikanArr as $key => $value) {
            array_push($tempPraktikan, $value);
        }
        array_push($allRating, $tempPraktikan);
    }

    $allNilai = array();

    foreach ($allKelas as $key => $kelas) {
        $allPraktikanArr = array();
        foreach ($allPraktikan as $key2 => $praktikan) {
            if($praktikan->kelas_id==$kelas->id){
                array_push($allPraktikanArr, $praktikan);
            }
        }
        $allPraktikanArr = collect($allPraktikanArr);
        $allPraktikanArr = $allPraktikanArr->sortByDesc('total');
        $tempPraktikan = array();
        foreach ($allPraktikanArr as $key => $value) {
            array_push($tempPraktikan, $value);
        }
        array_push($allNilai, $tempPraktikan);
    }
    $RatingPrivilege = array(1,2,4,5,8,16);
    if(in_array($userRole->id,$RatingPrivilege,true)){
    return Inertia::render('Rating', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allRating' => $allRating,
        'allNilai' => $allNilai,
        'allKelas' => $allKelas,
    ]);
    }else return redirect('/');
    
})->name('rating')->middleware('loggedIn:asisten');

//All Laporan for Assistant feature
Route::get('/allLaporan', function () {
    $user = Auth::guard('asisten')->user();
    $userRole = Role::where('id', $user->role_id)->first();
    $comingFrom = request('comingFrom') === null ? 'none':request('comingFrom');
    $position = request('position') === null ? 0:request('position');
    $allModul = Modul::orderBy('isEnglish','asc')->get();
    $allHistory = DB::table('laporan__pjs')
                    ->join('moduls', 'laporan__pjs.modul_id', '=', 'moduls.id')
                    ->select('laporan__pjs.*', 'moduls.judul')->orderBy('created_at','desc')->get();

    $allLaporanPrivilege = array(1,2,4,5,6);
    if(in_array($userRole->id,$allLaporanPrivilege,true)){
    return Inertia::render('Laporan', [
        'comingFrom' => $comingFrom,
        'currentUser' => $user,
        'position' => $position,
        'userRole' => $userRole->role,
        'allModul' => $allModul,
        'allHistory' => $allHistory,
    ]);
    }else return redirect('/'); 

})->name('allLaporan')->middleware('loggedIn:asisten');

Route::post('/updateDesc', [\App\Http\Controllers\AsistenController::class, 'updateDesc'])->name('updateDesc')->middleware('loggedIn:asisten');

Route::post('/resetPass', [\App\Http\Controllers\PraktikanController::class, 'resetPass'])->name('resetPassword')->middleware('loggedIn:praktikan');

Route::get('/contact_asisten', function () {
    $user = Auth::guard('praktikan')->user();
    $allAsisten = Asisten::orderBy('kode','asc')->get();
    return Inertia::render('ContactAsisten',[
        'allAsisten' => $allAsisten,
        'user'=> $user,
    ]);
})->name('contact_asisten')->middleware('loggedIn:praktikan');
