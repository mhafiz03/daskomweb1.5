<?php

namespace App\Http\Controllers;

use App\Models\JadwalJaga;
use App\Models\Kelas;
use App\Models\Asisten;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JadwalJagaController extends Controller
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
            'kelas_id'   => 'required',
            'asisten_id' => 'required',
        ]);
        
        foreach (JadwalJaga::all() as $modul => $value) 
            if($value->kelas_id === $request->kelas_id &&
                $value->asisten_id === $request->asisten_id){

            return '{"message": "Asisten '. 
                        Asisten::find($request->asisten_id)->kode .
                    ' sudah ada dalam kelas '. 
                        Kelas::find($request->kelas_id)->kelas .
                    '"}';   
        }
         
        JadwalJaga::create([
            'kelas_id'     => $request->kelas_id,
            'asisten_id'   => $request->asisten_id,
        ]);

        $id = JadwalJaga::where('kelas_id', $request->kelas_id)
                ->where('asisten_id', $request->asisten_id)->first()->id;

        return '{"message": "success", "id": '. $id .'}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JadwalJaga  $jadwal_Jaga
     * @return \Illuminate\Http\Response
     */
    public function show(JadwalJaga $jadwal_Jaga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JadwalJaga  $jadwal_Jaga
     * @return \Illuminate\Http\Response
     */
    public function edit(JadwalJaga $jadwal_Jaga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JadwalJaga  $jadwal_Jaga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JadwalJaga $jadwal_Jaga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JadwalJaga  $jadwal_Jaga
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $plotting = JadwalJaga::where('asisten_id', $request->asisten_id)
                        ->where('kelas_id', $request->kelas_id)->first();

        if($plotting === null)
            return '{"message": "Kombinasi asisten & kelas tidak ditemukan"}';

        $plotting->delete();

        return '{"message": "success"}';
    }

    /**
     * Remove all jadwal from table.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        DB::table('jadwal_jagas')->delete();

        return '{"message": "success"}';
    }
}
