<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Role;
use App\Models\Kelas;
use App\Models\Feedback;
use App\Models\Modul;
use App\Models\Asisten;
use App\Models\Configuration;
use App\Models\LaporanPraktikan;
use App\Models\Nilai;
use App\Models\Praktikan;
use App\Models\Polling;
use App\Models\JenisPolling;

class PageController extends Controller
{
    /**
     * Get common request parameters
     */
    private function getCommonParams()
    {
        return [
            'comingFrom' => request('comingFrom') ?? 'none',
            'position' => request('position') ?? 0,
        ];
    }

    /**
     * Welcome page
     */
    public function welcome()
    {
        return Inertia::render('Welcome', $this->getCommonParams());
    }

    /**
     * About page
     */
    public function about()
    {
        return Inertia::render('About', $this->getCommonParams());
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return Inertia::render('Contact', $this->getCommonParams());
    }

    /**
     * Login page
     */
    public function login()
    {
        $all_kelas = Kelas::where('id', '!=', 12)->orderBy('kelas', 'asc')->get();
        $roles = Role::all();
        
        return Inertia::render('Login', array_merge($this->getCommonParams(), [
            'all_kelas' => $all_kelas,
            'roles' => $roles
        ]));
    }

    /**
     * Asisten dashboard
     */
    public function asisten()
    {
        $user = Auth::guard('asisten')->user();
        $messages = Feedback::where('asisten_id', $user->id)
            ->leftJoin('kelas', 'feedback.kelas_id', '=', 'kelas.id')
            ->leftJoin('praktikans', 'feedback.praktikan_id', '=', 'praktikans.id')
            ->orderBy('feedback.created_at', 'desc')->get();
        $userRole = Role::where('id', $user->role_id)->first();
        
        return Inertia::render('Asisten', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'messages' => $messages,
            'userRole' => $userRole->role,
        ]));
    }

    /**
     * Praktikan dashboard
     */
    public function praktikan()
    {
        $user = Auth::guard('praktikan')->user();
        $user->kelas = Kelas::where('id', $user->kelas_id)->first()->kelas;
        $allAsisten = Asisten::orderBy('kode', 'asc')->get();
        $allAsistenPolling = Asisten::where('kode', '!=', 'BOT')->orderBy('kode', 'asc')->get();
        $isRunmod = Configuration::find(1)->runmod_activation;
        $pollingComplete = Polling::where('praktikan_id', $user->id)->exists();
        $allPolling = JenisPolling::all();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();
        $allJurnal = DB::table('soal_jurnals')
            ->join('moduls', 'soal_jurnals.modul_id', '=', 'moduls.id')
            ->select('soal_jurnals.*', 'moduls.judul')->get();
        
        return Inertia::render('Praktikan', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'allAsisten' => $allAsisten,
            'allAsistenPolling' => $allAsistenPolling,
            'isRunmod' => $isRunmod,
            'pollingComplete' => $pollingComplete,
            'allPolling' => $allPolling,
            'allModul' => $allModul,
            'allJurnal' => $allJurnal,
        ]));
    }

    /**
     * Soal management page
     */
    public function soal()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();
        $allTP = DB::table('soal_tps')
            ->join('moduls', 'soal_tps.modul_id', '=', 'moduls.id')
            ->select('soal_tps.*', 'moduls.judul')->get();
        $allTA = DB::table('soal_tas')
            ->join('moduls', 'soal_tas.modul_id', '=', 'moduls.id')
            ->select('soal_tas.*', 'moduls.judul')->get();
        $allTK = DB::table('soal_tks')
            ->join('moduls', 'soal_tks.modul_id', '=', 'moduls.id')
            ->select('soal_tks.*', 'moduls.judul')->get();
        $allJurnal = DB::table('soal_jurnals')
            ->join('moduls', 'soal_jurnals.modul_id', '=', 'moduls.id')
            ->select('soal_jurnals.*', 'moduls.judul')->get();
        $allMandiri = DB::table('soal_mandiris')
            ->join('moduls', 'soal_mandiris.modul_id', '=', 'moduls.id')
            ->select('soal_mandiris.*', 'moduls.judul')->get();
        $allFITB = DB::table('soal_fitbs')
            ->join('moduls', 'soal_fitbs.modul_id', '=', 'moduls.id')
            ->select('soal_fitbs.*', 'moduls.judul')->get();

        return Inertia::render('Soal', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
            'allTP' => $allTP,
            'allTA' => $allTA,
            'allTK' => $allTK,
            'allJurnal' => $allJurnal,
            'allMandiri' => $allMandiri,
            'allFITB' => $allFITB,
        ]));
    }

    /**
     * Kelas management page
     */
    public function kelas()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allKelas = Kelas::all();
        $kelasPrivilege = [1, 2, 4, 5];
        
        if (!in_array($userRole->id, $kelasPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Kelas', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allKelas' => $allKelas,
        ]));
    }

    /**
     * Modul management page
     */
    public function modul()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();
        $modulPrivilege = [1, 2, 4, 15, 7];
        
        if (!in_array($userRole->id, $modulPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Modul', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
        ]));
    }

    /**
     * Plotting page
     */
    public function plotting()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allJaga = DB::table('jadwal_jagas')
            ->join('asistens', 'jadwal_jagas.asisten_id', '=', 'asistens.id')
            ->join('kelas', 'jadwal_jagas.kelas_id', '=', 'kelas.id')
            ->select('jadwal_jagas.*', 'asistens.kode', 'kelas.kelas', 'kelas.hari', 'kelas.shift')->get();
        $allKelas = Kelas::all();
        $allAsisten = Asisten::orderBy('kode', 'asc')->get();
        $plottingPrivilege = [1, 2, 4, 5];
        
        if (!in_array($userRole->id, $plottingPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Plotting', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allJaga' => $allJaga,
            'allKelas' => $allKelas,
            'allAsisten' => $allAsisten,
        ]));
    }

    /**
     * Praktikum page
     */
    public function praktikum()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allKelas = Kelas::all();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();
        $isRunmod = Configuration::find(1)->runmod_activation;
        
        return Inertia::render('Praktikum', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allKelas' => $allKelas,
            'allModul' => $allModul,
            'isRunmod' => $isRunmod,
        ]));
    }

    /**
     * Konfigurasi page
     */
    public function konfigurasi()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $currentConfig = Configuration::find(1);
        $konfigurasiPrivilege = [1, 2, 4, 18, 7];
        
        if (!in_array($userRole->id, $konfigurasiPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Konfigurasi', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'currentConfig' => $currentConfig ?? 'nope',
        ]));
    }

    /**
     * Jawaban page
     */
    public function jawaban()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allModul = Modul::where('isEnglish', 0)
            ->where('id', '<', '20')
            ->orderBy('id', 'asc')
            ->get();
        $jawabanPrivilege = [1, 2, 7, 11, 15];
        
        if (!in_array($userRole->id, $jawabanPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Jawaban', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
        ]));
    }

    /**
     * Pelanggaran page
     */
    public function pelanggaran()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allAsisten = Asisten::orderBy('kode', 'asc')->get();
        
        foreach ($allAsisten as $asisten => $asisten_val) {
            $allLaporan = LaporanPraktikan::where('laporan_praktikans.asisten_id', $asisten_val->id)
                ->join('praktikans', 'laporan_praktikans.praktikan_id', '=', 'praktikans.id')
                ->select('laporan_praktikans.*', 'praktikans.nama', 'praktikans.nim', 'praktikans.kelas_id')
                ->latest()
                ->get();

            $nilaiUnexists = 0;
            foreach ($allLaporan as $laporan => $value) {
                if (Nilai::where('praktikan_id', $value->praktikan_id)
                    ->where('modul_id', $value->modul_id)
                    ->where('asisten_id', $value->asisten_id)
                    ->exists()) {
                    $value->nilaiExists = true;
                } else {
                    $nilaiUnexists++;
                    $value->nilaiExists = false;
                }
            }

            $asisten_val->nilaiUnexists = $nilaiUnexists;
        }
        
        $pelanggaranPrivilege = [1, 2, 4, 5, 6, 18];
        if (!in_array($userRole->id, $pelanggaranPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Pelanggaran', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allAsisten' => $allAsisten,
        ]));
    }

    /**
     * Polling page
     */
    public function polling()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $jenisPollings = JenisPolling::all();
        $allAsisten = Asisten::all();
        $pollingResults = [];

        foreach ($allAsisten as $each_asisten => $asisten) {
            $allPolling = [];
            foreach ($jenisPollings as $each_jenis => $jenis) {
                $jumlahPoll = Polling::where('polling_id', $jenis->id)
                    ->where('asisten_id', $asisten->id)
                    ->count();

                $allPolling[] = (object)[
                    'jenis' => $jenis->judul,
                    'jumlah_poll' => $jumlahPoll
                ];
            }

            $pollingResults[] = (object)[
                'id' => $asisten->id,
                'kode' => $asisten->kode,
                'nama' => $asisten->nama,
                'polling' => $allPolling
            ];
        }

        return Inertia::render('Polling', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allJenisPollings' => $jenisPollings,
            'allPollingResults' => $pollingResults,
        ]));
    }

    /**
     * Tugas Pendahuluan page
     */
    public function tp()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allTP = DB::table('tugaspendahuluans')
            ->join('moduls', 'tugaspendahuluans.modul_id', '=', 'moduls.id')
            ->select('tugaspendahuluans.*', 'moduls.judul', 'moduls.isEnglish')->get();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();

        if ($allTP !== null) {
            foreach ($allTP as $key => $value) {
                $value->isActive = $value->isActive == '1';
            }
        }
        
        $tpPrivilege = [1, 2, 15, 11, 7];
        if (!in_array($userRole->id, $tpPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('TugasPendahuluan', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allTP' => $allTP ?? 'nope',
            'allModul' => $allModul,
        ]));
    }

    /**
     * Nilai page
     */
    public function nilai()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allLaporan = LaporanPraktikan::where('laporan_praktikans.asisten_id', $user->id)
            ->join('praktikans', 'laporan_praktikans.praktikan_id', '=', 'praktikans.id')
            ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
            ->select('laporan_praktikans.*', 'praktikans.nama', 'praktikans.nim', 'praktikans.kelas_id', 'kelas', 'kelas.shift', 'kelas.hari')
            ->latest()
            ->get();
        
        foreach ($allLaporan as $laporan => $value) {
            if (Nilai::where('praktikan_id', $value->praktikan_id)
                ->where('modul_id', $value->modul_id)
                ->where('asisten_id', $value->asisten_id)
                ->exists()) {
                $value->nilaiExists = true;
            } else {
                $value->nilaiExists = false;
            }
        }

        return Inertia::render('Nilai', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allLaporan' => $allLaporan ?? [],
        ]));
    }

    /**
     * History page
     */
    public function history()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $asistenExist = false;
        $allAsistenHistory = [];
        $allHistory = DB::table('laporan_pjs')
            ->join('moduls', 'laporan_pjs.modul_id', '=', 'moduls.id')
            ->select('laporan_pjs.*', 'moduls.judul')->orderBy('created_at', 'desc')->get();
        
        foreach ($allHistory as $history => $h) {
            $asistenExist = false;
            foreach (explode('-', $h->allasisten_id) as $asisten => $a) {
                if ($a == $user->id) {
                    $asistenExist = true;
                }
            }

            if ($asistenExist) {
                array_push($allAsistenHistory, $h);
            }
        }

        return Inertia::render('History', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allHistory' => $allAsistenHistory ?? 'nope',
        ]));
    }

    /**
     * Set Praktikan page
     */
    public function setpraktikan()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();

        return Inertia::render('SetPraktikan', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
        ]));
    }

    /**
     * Lihat TP page
     */
    public function lihatTp()
    {
        $user = Auth::guard('asisten')->user();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();

        return Inertia::render('Lihat_Tp', [
            'currentUser' => $user,
            'allModul' => $allModul,
        ]);
    }

    /**
     * Rating page
     */
    public function rating()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allPraktikan = Praktikan::join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
            ->select('praktikans.id', 'praktikans.nama', 'praktikans.nim', 'kelas.kelas', 'praktikans.kelas_id')
            ->where('kelas.id', '!=', 12)
            ->get();
        $allKelas = Kelas::where('kelas.id', '!=', 12)->orderBy('kelas', 'asc')->get();
        
        foreach ($allPraktikan as $praktikan => $prak_value) {
            $allLaporan = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('rating');
            $allTP = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('tp');
            $allTA = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('ta');
            $allJurnal = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('jurnal');
            $allTK = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('tk');
            $allSkill = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('skill');
            $allDiskon = Nilai::where('nilais.praktikan_id', $prak_value->id)
                ->join('praktikans', 'nilais.praktikan_id', '=', 'praktikans.id')
                ->join('kelas', 'praktikans.kelas_id', '=', 'kelas.id')
                ->select('nilais.rating')
                ->sum('diskon');
            
            $prak_value->tp = $allTP;
            $prak_value->ta = $allTA;
            $prak_value->jurnal = $allJurnal;
            $prak_value->tk = $allTK;
            $prak_value->diskon = $allDiskon;
            $prak_value->total = ($allTP * 0.15) + ($allTA * 0.15) + ($allJurnal * 0.1) + ($allTK * 0.2) - ($allDiskon * 0.01);
            $prak_value->rating = $allLaporan;
        }

        $allRating = [];
        foreach ($allKelas as $key => $kelas) {
            $allPraktikanArr = [];
            foreach ($allPraktikan as $key2 => $praktikan) {
                if ($praktikan->kelas_id == $kelas->id) {
                    array_push($allPraktikanArr, $praktikan);
                }
            }
            $allPraktikanArr = collect($allPraktikanArr);
            $allPraktikanArr = $allPraktikanArr->sortByDesc('rating');
            $tempPraktikan = [];
            foreach ($allPraktikanArr as $key => $value) {
                array_push($tempPraktikan, $value);
            }
            array_push($allRating, $tempPraktikan);
        }

        $allNilai = [];
        foreach ($allKelas as $key => $kelas) {
            $allPraktikanArr = [];
            foreach ($allPraktikan as $key2 => $praktikan) {
                if ($praktikan->kelas_id == $kelas->id) {
                    array_push($allPraktikanArr, $praktikan);
                }
            }
            $allPraktikanArr = collect($allPraktikanArr);
            $allPraktikanArr = $allPraktikanArr->sortByDesc('total');
            $tempPraktikan = [];
            foreach ($allPraktikanArr as $key => $value) {
                array_push($tempPraktikan, $value);
            }
            array_push($allNilai, $tempPraktikan);
        }
        
        $RatingPrivilege = [1, 2, 4, 5, 8, 16];
        if (!in_array($userRole->id, $RatingPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Rating', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allRating' => $allRating,
            'allNilai' => $allNilai,
            'allKelas' => $allKelas,
        ]));
    }

    /**
     * All Laporan page
     */
    public function allLaporan()
    {
        $user = Auth::guard('asisten')->user();
        $userRole = Role::where('id', $user->role_id)->first();
        $allModul = Modul::orderBy('isEnglish', 'asc')->get();
        $allHistory = DB::table('laporan_pjs')
            ->join('moduls', 'laporan_pjs.modul_id', '=', 'moduls.id')
            ->select('laporan_pjs.*', 'moduls.judul')->orderBy('created_at', 'desc')->get();

        $allLaporanPrivilege = [1, 2, 4, 5, 6];
        if (!in_array($userRole->id, $allLaporanPrivilege, true)) {
            return redirect('/');
        }
        
        return Inertia::render('Laporan', array_merge($this->getCommonParams(), [
            'currentUser' => $user,
            'userRole' => $userRole->role,
            'allModul' => $allModul,
            'allHistory' => $allHistory,
        ]));
    }

    /**
     * Contact Asisten page for praktikan
     */
    public function contactAsisten()
    {
        $user = Auth::guard('praktikan')->user();
        $allAsisten = Asisten::orderBy('kode', 'asc')->get();
        
        return Inertia::render('ContactAsisten', [
            'allAsisten' => $allAsisten,
            'user' => $user,
        ]);
    }
}
