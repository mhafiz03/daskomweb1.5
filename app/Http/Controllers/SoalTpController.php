<?php

namespace App\Http\Controllers;

use App\Models\SoalTp;
use App\Models\Modul;
use App\Models\Configuration;
use App\Models\Tugaspendahuluan;
use App\Models\JawabanTp;
use Illuminate\Http\Request;

class SoalTpController extends Controller
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
            'soal'        => 'required|unique:soal_tps|string',
            'isEssay'     => 'required',
            'isProgram'   => 'required',
            'modul_id'    => 'required',
        ]);

        SoalTp::create([
            'soal'        => $request->soal,
            'isEssay'     => $request->isEssay,
            'isProgram'   => $request->isProgram,
            'modul_id'    => $request->modul_id,
        ]);

        $id = SoalTp::where('soal', $request->soal)->first()->id;

        return '{"message": "success", "id": '. $id .'}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoalTp  $soal_Tp
     * @return \Illuminate\Http\Response
     */
    public function show($isEnglish, $praktikan_id)
    {
        if(!Configuration::find(1)->tp_activation)
            return '{"message": "nope"}';

        $allTP = Tugaspendahuluan::where('isActive', true)->get();
        foreach ($allTP as $tp => $value) {
        
            if(JawabanTp::where('praktikan_id', $praktikan_id)
                ->where('modul_id', $value->modul_id)
                ->exists()){

                $allJawabanTP = JawabanTp::where('praktikan_id', $praktikan_id)
                ->where('jawaban_tps.modul_id', $value->modul_id)
                ->join('soal_tps', 'jawaban_tps.soal_id', '=', 'soal_tps.id')
                ->select('jawaban_tps.*', 'soal_tps.soal', 'soal_tps.isEssay', 'soal_tps.isProgram')
                ->get();

                $all_soalEssay=[];
                $all_soalProgram=[];

                foreach($allJawabanTP as $jawabanTp) {

                    if($jawabanTp->isEssay)
                        array_push($all_soalEssay, $jawabanTp); 
                    else
                        array_push($all_soalProgram, $jawabanTp);
                }

                return response()->json([
                    'message'=> 'success',
                    'all_soalEssay' => $all_soalEssay,
                    'all_soalProgram' => $all_soalProgram,
                ], 200);
            }
        }

        if($isEnglish === 'true') {

            foreach ($allTP as $tp => $value) {
                if(Modul::where('id', $value->modul_id)->first()->isEnglish){

                    $all_soalEssay = SoalTp::where('modul_id', $value->modul_id)
                                ->where('isEssay', true)
                                ->take(5)->get();
                    $all_soalProgram = SoalTp::where('modul_id', $value->modul_id)
                                ->where('isProgram', true)
                                ->take(3)->get();

                    return response()->json([
                        'message'=> 'success',
                        'all_soalEssay' => $all_soalEssay,
                        'all_soalProgram' => $all_soalProgram,
                    ], 200);
                }
            }

        } else {

            foreach ($allTP as $tp => $value) {
                if(!Modul::where('id', $value->modul_id)->first()->isEnglish){
                    $all_soalEssay = SoalTp::where('modul_id', $value->modul_id)
                                ->where('isEssay', true)
                                ->take(5)->get();
                    $all_soalProgram = SoalTp::where('modul_id', $value->modul_id)
                                ->where('isProgram', true)
                                ->take(3)->get();

                    return response()->json([
                        'message'=> 'success',
                        'all_soalEssay' => $all_soalEssay,
                        'all_soalProgram' => $all_soalProgram,
                    ], 200);
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoalTp  $soal_Tp
     * @return \Illuminate\Http\Response
     */
    public function edit(SoalTp $soal_Tp)
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
            'soal'       => 'required|string',
            'isEssay'    => 'required',
            'isProgram'  => 'required',
            'modul_id'   => 'required',
        ]);    

        if($request->soal != $request->oldSoal)
            foreach (SoalTp::all() as $soal => $value) 
                if($value->soal === $request->soal)
                    return '{"message": "Soal '. $request->soal .' sudah terdaftar"}';

        $soal = SoalTp::find($request->id);
        $soal->soal = $request->soal;

        if($request->jenisSoal === "essay"){
            $soal->isEssay = 1;
            $soal->isProgram = 0;
        } else if($request->jenisSoal === "program"){
            $soal->isEssay = 0;
            $soal->isProgram = 1;
        }
        $soal->modul_id = $request->modul_id;
        $soal->save();

        $soal->id = $request->id;
        $soal->soal = $request->soal;
        if($request->jenisSoal === "essay"){
            $soal->isEssay = 1;
            $soal->isProgram = 0;
        } else if($request->jenisSoal === "program"){
            $soal->isEssay = 0;
            $soal->isProgram = 1;
        }
        $soal->modul_id = $request->modul_id;

        return response()->json([
            'message'=> 'success',
            'soal' => $soal,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soal = SoalTp::find($id);
        $soal->delete();

        return '{"message": "success"}';
    }
}
