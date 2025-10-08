<?php

namespace App\Http\Controllers;

use App\Models\SoalTa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SoalTaController extends Controller
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
        $request->validate([
            'pertanyaan'       => 'required|unique:soal_tas|string',
            'modul_id'         => 'required',
            'jawaban_benar'    => 'required',
            'jawaban_salah1'   => 'required',
            'jawaban_salah2'   => 'required',
            'jawaban_salah3'   => 'required',
        ]);

        if($request->jawaban_benar === $request->jawaban_salah1 ||
            $request->jawaban_benar === $request->jawaban_salah2 ||
            $request->jawaban_benar === $request->jawaban_salah3 ||
            $request->jawaban_salah1 === $request->jawaban_salah2 ||
            $request->jawaban_salah1 === $request->jawaban_salah3 ||
            $request->jawaban_salah2 === $request->jawaban_salah3 ) {

            return '{"message": "Tidak boleh ada jawaban yang sama"}';
        }

        SoalTa::create([
            'pertanyaan'       => $request->pertanyaan,
            'modul_id'         => $request->modul_id,
            'jawaban_benar'    => $request->jawaban_benar,
            'jawaban_salah1'   => $request->jawaban_salah1,
            'jawaban_salah2'   => $request->jawaban_salah2,
            'jawaban_salah3'   => $request->jawaban_salah3,
        ]);

        $id = SoalTa::where('pertanyaan', $request->pertanyaan)->first()->id;

        return '{"message": "success", "id": '. $id .'}';
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($modul_id, $kelas_id)
    {
        if(substr(Kelas::where('id', $kelas_id)->first()->kelas, 0, 3) === 'TOT') {

            $all_soal = SoalTa::where('modul_id', $modul_id)->get();

        } else {

            $all_soal = SoalTa::where('modul_id', $modul_id)->inRandomOrder()->take(10)->get();
        }
        return response()->json([
            'message'=> 'success',
            'all_soal' => $all_soal,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoalTa  $soal_Ta
     * @return \Illuminate\Http\Response
     */
    public function edit(SoalTa $soal_Ta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoalTa  $soal_Ta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'pertanyaan'       => 'required|string',
            'modul_id'         => 'required',
            'jawaban_benar'    => 'required|string',
            'jawaban_salah1'   => 'required|string',
            'jawaban_salah2'   => 'required|string',
            'jawaban_salah3'   => 'required|string',
        ]);

        if($request->pertanyaan != $request->oldPertanyaan)
            foreach (SoalTa::all() as $soal => $value) 
                if($value->pertanyaan === $request->pertanyaan)
                    return '{"message": "Soal '. $request->pertanyaan .' sudah terdaftar"}';

        $soal = SoalTa::find($request->id);
        $soal->pertanyaan = $request->pertanyaan;
        $soal->jawaban_benar = $request->jawaban_benar;
        $soal->jawaban_salah1 = $request->jawaban_salah1;
        $soal->jawaban_salah2 = $request->jawaban_salah2;
        $soal->jawaban_salah3 = $request->jawaban_salah3;
        $soal->modul_id = $request->modul_id;
        $soal->save();

        $soal->id = $request->id;
        $soal->pertanyaan = $request->pertanyaan;
        $soal->jawaban_benar = $request->jawaban_benar;
        $soal->jawaban_salah1 = $request->jawaban_salah1;
        $soal->jawaban_salah2 = $request->jawaban_salah2;
        $soal->jawaban_salah3 = $request->jawaban_salah3;
        $soal->modul_id = $request->modul_id;

        return response()->json([
            'message'=> 'success',
            'soal' => $soal,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoalTa  $soal_Ta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $soal = SoalTa::find($id);
    
            if (!$soal) {
                return response()->json(['message' => 'Soal not found'], 404);
            }
    
            $soal->delete();
    
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to delete SoalTa: '.$e->getMessage());
    
            return response()->json([
                'message' => 'Failed to delete soal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
