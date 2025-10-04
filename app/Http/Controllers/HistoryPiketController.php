<?php

namespace App\Http\Controllers;

use App\Models\HistoryPiket;
use Illuminate\Http\Request;

class HistoryPiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        HistoryPiket::create([
            'hari'        => $request->hari,
            'shift'       => $request->shift,
            'status'      => $request->pj,
            'asisten_id'  => $request->asisten_id,
            'modul_id'    => $request->modul_id,
        ]);

        return '{"message": "success"}';
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryPiket  $history_Piket
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryPiket $history_Piket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HistoryPiket  $history_Piket
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoryPiket $history_Piket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryPiket  $history_Piket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HistoryPiket $history_Piket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryPiket  $history_Piket
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoryPiket $history_Piket)
    {
        //
    }
}
