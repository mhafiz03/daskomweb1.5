<?php

namespace App\Http\Controllers;

use App\Models\HistoryJaga;
use App\Models\Asisten;
use Illuminate\Http\Request;

class HistoryJagaController extends Controller
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
        if($request->asisten_id != null)
            HistoryJaga::create([
                'hari'          => $request->hari,
                'shift'         => $request->shift,
                'pj'            => $request->pj,
                'modul_id'      => $request->modul_id,
                'asisten_id'    => $request->asisten_id,
            ]);
        else {
            if($request->allasisten_kode != ''){
                foreach (explode("-", $request->allasisten_kode) as $kode => $value) {
                    
                    HistoryJaga::create([
                        'hari'          => $request->hari,
                        'shift'         => $request->shift,
                        'pj'            => $request->pj,
                        'modul_id'      => $request->modul_id,
                        'asisten_id'    => Asisten::where('kode', $value)->first()->id,
                    ]); 
                }
            }
        }

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
            'latestPJHistory' => HistoryJaga::where('pj', 1)->orderBy('created_at', 'desc')->first(),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HistoryJaga  $history_Jaga
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoryJaga $history_Jaga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryJaga  $history_Jaga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HistoryJaga $history_Jaga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $praktikum = HistoryJaga::where('hari', $request->hari)
                ->where('shift', $request->shift)
                ->where('pj', $request->pj)
                ->where('modul_id', $request->modul_id)
                ->where('asisten_id', $request->asisten_id)
                ->first();
        $praktikum->delete();

        return '{"message": "success"}';
    }
}
