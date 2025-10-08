<?php

namespace App\Http\Controllers;

use App\Models\Asisten;
use App\Models\Kelas;
use App\Models\Configuration;
use App\Models\HistoryJaga;
use App\Models\LaporanPraktikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AsistenController extends Controller
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
            'nama'          => 'required|unique:asistens|unique:praktikans|string',
            'kode'          => 'required|unique:asistens|size:3|string',
            'password'      => 'required|min:6|string',
            'role_id'       => 'required|integer',
            'deskripsi'     => 'required|min:10|string',
            'nomor_telepon' => 'required|min:1|max:14',
            'id_line'       => 'required|min:4|max:30',
            'instagram'     => 'required|min:1|max:30',
        ]);

        if(!Configuration::find(1)->registrationAsisten_activation)
            return '{"message": "Registrasi untuk asisten telah ditutup"}';

        Asisten::create([
            'nama'          => $request->nama,
            'kode'          => strtoupper($request->kode),
            'password'      => Hash::make($request->password),
            'role_id'       => $request->role_id,
            'deskripsi'     => $request->deskripsi,
            'nomor_telepon' => $request->nomor_telepon,
            'id_line'       => $request->id_line,
            'instagram'     => $request->instagram,
        ]);

        return '{"message": "success"}';
    }

    public function updateDesc(Request $request){
        $user = Auth::guard('asisten')->user();
        $request->validate([
            'deskripsi'     => 'required|min:6|max:150',
            'nomor_telepon' => 'required|min:1|max:14',
            'id_line'       => 'required|min:4|max:30',
            'instagram'     => 'required|min:1|max:30',
        ]);

        $asisten = Asisten::where('id',$user->id)->update([
            'deskripsi'     => $request->deskripsi,
            'nomor_telepon' => $request->nomor_telepon,
            'id_line'       => $request->id_line,
            'instagram'     => $request->instagram,
        ]);

        return response()->json([
            'message'   => 'success',
            'asisten'   => $asisten,
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($asisten_id)
    {
        $allAsistenData = LaporanPraktikan::where('asisten_id', $asisten_id)->get();
        $allAsistenHistory = HistoryJaga::where('asisten_id', $asisten_id)->get();
        
        $ratingAsisten = 0;
        $gajiAsisten = 0;
        foreach ($allAsistenData as $key => $value) {
            $ratingAsisten += $value->rating_asisten;    
        }
        $intHistoryCount = 0;
        $regHistoryCount = 0;
        foreach ($allAsistenHistory as $history => $value)
            if(substr(Kelas::where('hari', $value->hari)->where('shift', $value->shift)->first()->kelas,6) === 'INT')
                $intHistoryCount++;

        $regHistoryCount = count($allAsistenHistory) - $intHistoryCount;

        if(count($allAsistenData) !== 0){
            $ratingAsisten /= count($allAsistenData);
        }

        return response()->json([
            'message'       => 'success',
            'ratingAsisten' => $ratingAsisten,
            'jagaReguler'   => $regHistoryCount,
            'jagaInter'     => $intHistoryCount,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asisten  $asisten
     * @return \Illuminate\Http\Response
     */
    public function edit(Asisten $asisten)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asisten  $asisten
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asisten $asisten)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asisten  $asisten
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asisten $asisten)
    {
        //
    }
}
