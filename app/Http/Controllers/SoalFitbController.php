<?php

namespace App\Http\Controllers;

use App\Models\SoalFitb;
use App\Models\TempSoaljurnal;
use Illuminate\Http\Request;

class SoalFitbController extends Controller
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
            'soal'        => 'required|unique:soal__fitbs|string',
            'modul_id'    => 'required',
        ]);

        SoalFitb::create([
            'soal'        => $request->soal,
            'modul_id'    => $request->modul_id,
        ]);

        $id = SoalFitb::where('soal', $request->soal)->first()->id;

        return '{"message": "success", "id": '. $id .'}';
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $all_soalFitbID = explode ("-", TempSoaljurnal::latest()->first()->allFitb_id);
        foreach ($all_soalFitbID as $soalId) {
            $all_soal[] = SoalFitb::find($soalId);
        }
        return response()->json([
            'message'=> 'success',
            'all_soal' => $all_soal,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoalFitb  $soal_Fitb
     * @return \Illuminate\Http\Response
     */
    public function edit(SoalFitb $soal_Fitb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'soal'        => 'required|string',
            'modul_id'    => 'required',
        ]);

        if($request->soal != $request->oldSoal)
            foreach (SoalFitb::all() as $soal => $value) 
                if($value->soal === $request->soal)
                    return '{"message": "Soal '. $request->soal .' sudah terdaftar"}';

        $soal = SoalFitb::find($request->id);
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
     * @param  \App\Models\SoalFitb  $soal_Fitb
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoalFitb $soal_Fitb)
    {
        //
    }
}
