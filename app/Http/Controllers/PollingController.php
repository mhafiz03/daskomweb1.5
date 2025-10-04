<?php

namespace App\Http\Controllers;

use App\Models\Polling;
use Illuminate\Http\Request;

class PollingController extends Controller
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
        for ($i=0; $i < count($request->all()); $i++) { 
            if($request->input($i.'.praktikan_id') == null || 
                $request->input($i.'.asisten_id') == null) {
                return '{"message": "Polling '. $request->input($i.'.judul') .' belum dipilih"}';
            }   
        } 

        for ($i=0; $i < count($request->all()); $i++) { 
          
            Polling::create([
                'praktikan_id'  => $request->input($i.'.praktikan_id'),
                'asisten_id'    => $request->input($i.'.asisten_id'),
                'polling_id'    => $request->input($i.'.id'),
            ]);    
        } 

        return '{"message": "success"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function show(Polling $polling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function edit(Polling $polling)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Polling $polling)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function destroy(Polling $polling)
    {
        //
    }
}
