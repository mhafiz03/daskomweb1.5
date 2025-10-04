<?php

namespace App\Http\Controllers;

use App\Models\JawabanTk;
use App\Models\SoalTk;
use Illuminate\Http\Request;

class JawabanTkController extends Controller
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
    public function store(Request $request)
    {
        JawabanTk::where('praktikan_id', $request->input('0.praktikan_id'))
                ->where('modul_id', $request->input('0.modul_id'))
                ->delete();
                 
        for ($i=0; $i < count($request->all()); $i++) { 
          
            JawabanTk::create([
                'praktikan_id'  => $request->input($i.'.praktikan_id'),
                'modul_id'      => $request->input($i.'.modul_id'),
                'soal_id'       => $request->input($i.'.soal_id'),
                'jawaban'       => $request->input($i.'.jawaban') == '' ? '-' : $request->input($i.'.jawaban'),
            ]);    
        } 

        $allJawabanTk = JawabanTk::where('praktikan_id', $request->input('0.praktikan_id'))
            ->where('modul_id', $request->input('0.modul_id'))
            ->get();

        $nilaiTkCorrect = 0;
        foreach ($allJawabanTk as $jawaban => $j) {
            $currentSoal = SoalTk::find($j->soal_id);
            if($j->jawaban == $currentSoal->jawaban_benar)
                $nilaiTkCorrect++;
        }

        $nilaiTk = $nilaiTkCorrect * /*Max Nilai*/100 / /*Max Soal*/10;

        return response()->json([
            'message' => 'success',
            'nilaiTk' => $nilaiTk,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JawabanTk  $jawaban_Tk
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JawabanTk  $jawaban_Tk
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JawabanTk  $jawaban_Tk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JawabanTk  $jawaban_Tk
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanTk $jawaban_Tk)
    {
        //
    }
}
