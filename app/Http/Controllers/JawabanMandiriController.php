<?php

namespace App\Http\Controllers;

use App\Models\JawabanMandiri;
use Illuminate\Http\Request;

class JawabanMandiriController extends Controller
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
        JawabanMandiri::where('praktikan_id', $request->input('0.praktikan_id'))
                ->where('modul_id', $request->input('0.modul_id'))
                ->delete(); 

        for ($i=0; $i < count($request->all()); $i++) { 
          
            JawabanMandiri::create([
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
     * @param  \App\Models\JawabanMandiri  $jawaban_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanMandiri $jawaban_Mandiri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JawabanMandiri  $jawaban_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanMandiri $jawaban_Mandiri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JawabanMandiri  $jawaban_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanMandiri $jawaban_Mandiri)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JawabanMandiri  $jawaban_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanMandiri $jawaban_Mandiri)
    {
        //
    }
}
