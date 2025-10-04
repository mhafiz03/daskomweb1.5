<?php

namespace App\Http\Controllers;

use App\Models\LaporanPj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPjController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($modul_id)
    {
        return response()->json([
            'message' => 'success',
            'all_laporan' => LaporanPj::where('modul_id', $modul_id)->get()
        ], 200);
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
        
        LaporanPj::create([
            'allasisten_id' => $request->allasisten_id,
            'laporan'       => $request->laporan,
            'hari'          => $request->hari,
            'shift'         => $request->shift,
            'modul_id'      => $request->modul_id,
        ]);
        
        $id = LaporanPj::where('hari', $request->hari)
            ->where('shift', $request->shift)
            ->where('modul_id', $request->modul_id)
            ->first()->id;

        return '{"message": "success", "id": '. $id .'}';
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
            'latestLaporanID' => DB::table('laporan__pjs')->orderBy('created_at', 'desc')->first()->id,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LaporanPj  $laporan_Pj
     * @return \Illuminate\Http\Response
     */
    public function edit(LaporanPj $laporan_Pj)
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
        $laporan_Pj = LaporanPj::where('id', $request->id)->first();
        $laporan_Pj->laporan = $request->laporan;
        $laporan_Pj->save();

        return '{"message": "success"}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $laporan_Pj = LaporanPj::find($id);
        if($laporan_Pj != null)
            $laporan_Pj->delete();
    }
}
