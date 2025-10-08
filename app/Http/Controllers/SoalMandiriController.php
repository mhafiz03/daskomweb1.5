<?php

namespace App\Http\Controllers;

use App\Models\SoalMandiri;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SoalMandiriController extends Controller
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
            'soal'        => 'required|unique:soal_mandiris|string',
            'modul_id'    => 'required',
        ]);

        SoalMandiri::create([
            'soal'        => $request->soal,
            'modul_id'    => $request->modul_id,
        ]);

        $id = SoalMandiri::where('soal', $request->soal)->first()->id;

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

            $all_soal = SoalMandiri::where('modul_id', $modul_id)->get();
            
        } else {

            $all_soal = SoalMandiri::where('modul_id', $modul_id)->inRandomOrder()->take(1)->get();
        }
        return response()->json([
            'message'=> 'success',
            'all_soal' => $all_soal,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoalMandiri  $soal_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function edit(SoalMandiri $soal_Mandiri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoalMandiri  $soal_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'soal'        => 'required|string',
            'modul_id'    => 'required',
        ]);

        if($request->soal != $request->oldSoal)
            foreach (SoalMandiri::all() as $soal => $value) 
                if($value->soal === $request->soal)
                    return '{"message": "Soal '. $request->soal .' sudah terdaftar"}';

        $soal = SoalMandiri::find($request->id);
        $soal->soal = $request->soal;
        $soal->modul_id = $request->modul_id;
        $soal->save();

        $soal->id = $request->id;
        $soal->soal = $request->soal;
        $soal->modul_id = $request->modul_id;

        return response()->json([
            'message'=> 'success',
            'soal' => $soal,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoalMandiri  $soal_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $soal = SoalMandiri::find($id);
    
            if (!$soal) {
                return response()->json(['message' => 'Soal not found'], 404);
            }
    
            $soal->delete();
    
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to delete SoalMandiri: '.$e->getMessage());
    
            return response()->json([
                'message' => 'Failed to delete soal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
