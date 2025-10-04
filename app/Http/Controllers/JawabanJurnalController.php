<?php

namespace App\Http\Controllers;

use App\Models\JawabanJurnal;
use Illuminate\Http\Request;

class JawabanJurnalController extends Controller
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
        JawabanJurnal::where('praktikan_id', $request->input('0.praktikan_id'))
                ->where('modul_id', $request->input('0.modul_id'))
                ->delete(); 

        for ($i=0; $i < count($request->all()); $i++) { 
          
            JawabanJurnal::create([
                'praktikan_id'  => $request->input($i.'.praktikan_id'),
                'modul_id'      => $request->input($i.'.modul_id'),
                'soal_id'       => $request->input($i.'.soal_id'),
                'jawaban'       => $request->input($i.'.jawaban') == '' ? '-' : $request->input($i.'.jawaban'),
            ]);    
        } 

        return '{"message": "success"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JawabanJurnal  $jawaban_Jurnal
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanJurnal $jawaban_Jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JawabanJurnal  $jawaban_Jurnal
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanJurnal $jawaban_Jurnal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JawabanJurnal  $jawaban_Jurnal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanJurnal $jawaban_Jurnal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JawabanJurnal  $jawaban_Jurnal
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanJurnal $jawaban_Jurnal)
    {
        //
    }
}
