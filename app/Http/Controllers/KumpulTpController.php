<?php

namespace App\Http\Controllers;

use App\Models\KumpulTp;
use App\Models\TempJawabantp;
use App\Models\JawabanTp;
use App\Models\Praktikan;
use App\Models\Modul;
use Illuminate\Http\Request;

class KumpulTpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request) 
    {
        if(JawabanTp::where('praktikan_id', $request->input('0.praktikan_id'))
            ->where('modul_id', $request->input('0.modul_id'))
            ->exists()){
            
            for ($i=0; $i < count($request->all()); $i++) { 

                $jawaban_tp = JawabanTp::where('praktikan_id', $request->input($i.'.praktikan_id'))
                ->where('modul_id', $request->input($i.'.modul_id'))
                ->where('soal_id', $request->input($i.'.soal_id'))->first();

                $jawaban_tp->jawaban = $request->input($i.'.jawaban') == null ? 'empty' : $request->input($i.'.jawaban');

                $jawaban_tp->save();
            } 

            return response()->json([
                'message'=> 'success'
            ], 200);
        }

        for ($i=0; $i < count($request->all()); $i++) { 
          
            JawabanTp::create([
                'praktikan_id'  => $request->input($i.'.praktikan_id'),
                'modul_id'      => $request->input($i.'.modul_id'),
                'soal_id'       => $request->input($i.'.soal_id'),
                'jawaban'       => $request->input($i.'.jawaban') == null ? 'empty' : $request->input($i.'.jawaban'),
            ]);
        } 

        return response()->json([
            'message'=> 'success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(JawabanTp::where('praktikan_id', $request->praktikan_id)
            ->where('modul_id', $request->modul_id)
            ->exists()){
            
            return response()->json([
                'message'=> 'Anda sudah mengumpulkan TP pada modul ini',
                'success'=>'nope',
            ], 200);
        }

        KumpulTp::create([
            'kelas_id'  => $request->kelas_id,
            'modul_id'   => $request->modul_id,
            'praktikan_id'  => $request->praktikan_id,
        ]);

        foreach (explode("-", $request->allJawaban_id) as $jawaban => $value) {
            
            $tempJawaban = TempJawabantp::find($value);
            if($tempJawaban != null) {

                JawabanTp::create([
                    'praktikan_id'  => $tempJawaban->praktikan_id,
                    'modul_id'      => $tempJawaban->modul_id,
                    'soal_id'       => $tempJawaban->soal_id,
                    'jawaban'       => $tempJawaban->jawaban,
                ]);
            } else {

                return response()->json([
                    'message'=> 'Salah satu jawaban tidak ditemukan',
                    'success'=>'nope',
                ], 200);
            }
        }

        $praktikan = Praktikan::find($request->praktikan_id);
        $modul = Modul::find($request->modul_id);

        return response()->json([
            'message'=> $praktikan->nama.' berhasil mengumpulkan TP modul "'. $modul->judul .'"',
            'success'=>'yes',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($kelas_id, $modul_id)
    {
        $allKumpulTP = KumpulTp::where('kumpul__tps.kelas_id', $kelas_id)
            ->where('kumpul__tps.modul_id', $modul_id)
            ->leftJoin('moduls', 'kumpul__tps.modul_id', '=', 'moduls.id')
            ->leftJoin('praktikans', 'kumpul__tps.praktikan_id', '=', 'praktikans.id')
            ->get();

        return response()->json([
            'message'=> 'success',
            'allKumpulTP'=>$allKumpulTP,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KumpulTp  $kumpul_Tp
     * @return \Illuminate\Http\Response
     */
    public function edit(KumpulTp $kumpul_Tp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KumpulTp  $kumpul_Tp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KumpulTp $kumpul_Tp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KumpulTp  $kumpul_Tp
     * @return \Illuminate\Http\Response
     */
    public function destroy(KumpulTp $kumpul_Tp)
    {
        //
    }
}