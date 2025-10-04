<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CurrentPraktikum;
use App\Models\SoalJurnal;
use App\Models\SoalFitb;
use App\Models\TempSoaljurnal;
use App\Models\Kelas;
use App\Events\praktikumStatusUpdated;
use Illuminate\Http\Request;

class CurrentPraktikumController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // FOR RUNMOD ONLY
    public function create(Request $request)
    {
        DB::table('current_praktikums')->truncate();

        $praktikum = CurrentPraktikum::create([
            'asisten_id' => $request->asisten_id,
            'kelas_id'   => $request->kelas_id,
            'modul_id'   => $request->modul_id,
            'status'     => 123,
        ]);

        $all_soalJurnal = SoalJurnal::where('modul_id', $request->modul_id)->inRandomOrder()->take(2)->get();
        
        $all_soalJurnalID = '';
        for ($i=0; $i < count($all_soalJurnal); $i++) { 
            $all_soalJurnalID .= $all_soalJurnal[$i]->id;
            if($i !== count($all_soalJurnal)-1)
                $all_soalJurnalID .= '-';
        }
        TempSoaljurnal::create([
            'allJurnal_id' => $all_soalJurnalID,
            'allFitb_id' => "",
        ]);

        broadcast(new praktikumStatusUpdated($praktikum));

        return '{"message": "success"}';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // FOR USUAL PRAKTIKUM
    public function store(Request $request)
    {
        DB::table('current_praktikums')->truncate();

        $praktikum = CurrentPraktikum::create([
            'asisten_id' => $request->asisten_id,
            'kelas_id'   => $request->kelas_id,
            'modul_id'   => $request->modul_id,
            'status'     => 0,
        ]);

        if(substr(Kelas::where('id', $request->kelas_id)->first()->kelas, 0, 3) === 'TOT') {
            
            $soalJurnal_sedang = SoalJurnal::where('modul_id', $request->modul_id)
            ->where('isSulit', false)->get();

            $soalJurnal_sulit = SoalJurnal::where('modul_id', $request->modul_id)
                ->where('isSulit', true)->get();

            $all_soalFitb = SoalFitb::where('modul_id', $request->modul_id)->get();
            
        } else {

            $soalJurnal_sedang = SoalJurnal::where('modul_id', $request->modul_id)
            ->where('isSulit', false)->inRandomOrder()->take(1)->get();

            $soalJurnal_sulit = SoalJurnal::where('modul_id', $request->modul_id)
                ->where('isSulit', true)->inRandomOrder()->take(1)->get();

            $all_soalFitb = SoalFitb::where('modul_id', $request->modul_id)->take(3)->get();
        }
    
        $all_soalJurnalID = '';
        for ($i=0; $i < count($soalJurnal_sedang); $i++) { 
            $all_soalJurnalID .= $soalJurnal_sedang[$i]->id;
            if($i !== count($soalJurnal_sedang)-1)
                $all_soalJurnalID .= '-';
        }
        $all_soalJurnalID .= '-';
        for ($i=0; $i < count($soalJurnal_sulit); $i++) { 
            $all_soalJurnalID .= $soalJurnal_sulit[$i]->id;
            if($i !== count($soalJurnal_sulit)-1)
                $all_soalJurnalID .= '-';
        }
    
        $all_soalFitbID = '';
        for ($i=0; $i < count($all_soalFitb); $i++) { 
            $all_soalFitbID .= $all_soalFitb[$i]->id;
            if($i !== count($all_soalFitb)-1)
                $all_soalFitbID .= '-';
        }

        TempSoaljurnal::create([
            'allJurnal_id' => $all_soalJurnalID,
            'allFitb_id' => $all_soalFitbID,
        ]);

        broadcast(new praktikumStatusUpdated($praktikum));

        return '{"message": "success"}';
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json([
            'message' => 'success',
            'current_praktikum' => CurrentPraktikum::all()->first(),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrentPraktikum  $current_Praktikum
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrentPraktikum $current_Praktikum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrentPraktikum  $current_Praktikum
     * @return \Illuminate\Http\Response
     */
    public function update($status)
    {
        $praktikum = CurrentPraktikum::all()->first();
        $praktikum->status = $status;
        $praktikum->save();

        broadcast(new praktikumStatusUpdated($praktikum));

        return '{"message": "success"}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $praktikum = CurrentPraktikum::all()->first();
        $praktikum->status = 777; //lucky but nooo, cause praktikum just deleted :v
        $praktikum->save();
        broadcast(new praktikumStatusUpdated($praktikum));

        DB::table('current_praktikums')->truncate();
    }
}
