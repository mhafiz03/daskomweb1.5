<?php

namespace App\Http\Controllers;

use App\Models\JawabanFitb;
use Illuminate\Http\Request;

class JawabanFitbController extends Controller
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
        JawabanFitb::where('praktikan_id', $request->input('0.praktikan_id'))
                ->where('modul_id', $request->input('0.modul_id'))
                ->delete(); 

        for ($i=0; $i < count($request->all()); $i++) { 
          
            JawabanFitb::create([
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
     * @param  \App\Models\JawabanFitb  $jawaban_Fitb
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanFitb $jawaban_Fitb)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JawabanFitb  $jawaban_Fitb
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanFitb $jawaban_Fitb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JawabanFitb  $jawaban_Fitb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanFitb $jawaban_Fitb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JawabanFitb  $jawaban_Fitb
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanFitb $jawaban_Fitb)
    {
        //
    }
}
