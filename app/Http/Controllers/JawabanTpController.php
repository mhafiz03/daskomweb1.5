<?php

namespace App\Http\Controllers;

use App\Models\JawabanTp;
use App\Models\Praktikan;
use Illuminate\Http\Request;

class JawabanTpController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JawabanTp  $jawaban_Tp
     * @return \Illuminate\Http\Response
     */
    public function show($praktikan_nim, $modul_id)
    {
        if(!Praktikan::where('nim', $praktikan_nim)->exists()){

            return response()->json([
                'message' => 'Praktikan ini tidak ada dalam database'
            ], 200);
        }

        if(JawabanTp::where('praktikan_id', Praktikan::where('nim', $praktikan_nim)->first()->id)
            ->where('modul_id', $modul_id)
            ->exists()) {

            return response()->json([
                'message' => 'success',
                'all_tp' => JawabanTp::where('jawaban__tps.praktikan_id', Praktikan::where('nim', $praktikan_nim)->first()->id)
                    ->where('jawaban__tps.modul_id', $modul_id)
                    ->leftJoin('soal__tps', 'jawaban__tps.soal_id', '=', 'soal__tps.id')
                    ->get()
            ], 200);

        } else {

            return response()->json([
                'message' => 'Praktikan ini tidak mengumpulkan TP'
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JawabanTp  $jawaban_Tp
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanTp $jawaban_Tp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JawabanTp  $jawaban_Tp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanTp $jawaban_Tp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JawabanTp  $jawaban_Tp
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanTp $jawaban_Tp)
    {
        //
    }
}
