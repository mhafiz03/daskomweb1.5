<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\SoalMandiri;
use Illuminate\Http\Request;

class SoalMandiriController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'soal' => 'required|unique:soal_mandiris|string',
            'modul_id' => 'required',
        ]);

        SoalMandiri::create([
            'soal' => $request->soal,
            'modul_id' => $request->modul_id,
        ]);

        $id = SoalMandiri::where('soal', $request->soal)->first()->id;

        return '{"message": "success", "id": '.$id.'}';
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($modul_id, $kelas_id)
    {
        if (substr(Kelas::where('id', $kelas_id)->first()->kelas, 0, 3) === 'TOT') {

            $all_soal = SoalMandiri::where('modul_id', $modul_id)->get();

        } else {

            $all_soal = SoalMandiri::where('modul_id', $modul_id)->inRandomOrder()->take(1)->get();
        }

        return response()->json([
            'message' => 'success',
            'all_soal' => $all_soal,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(SoalMandiri $soal_Mandiri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\SoalMandiri  $soal_Mandiri
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'soal' => 'required|string',
            'modul_id' => 'required',
        ]);

        if ($request->soal != $request->oldSoal) {
            foreach (SoalMandiri::all() as $soal => $value) {
                if ($value->soal === $request->soal) {
                    return '{"message": "Soal '.$request->soal.' sudah terdaftar"}';
                }
            }
        }

        $soal = SoalMandiri::find($request->id);
        $soal->soal = $request->soal;
        $soal->modul_id = $request->modul_id;
        $soal->save();

        $soal->id = $request->id;
        $soal->soal = $request->soal;
        $soal->modul_id = $request->modul_id;

        return response()->json([
            'message' => 'success',
            'soal' => $soal,
        ], 200);
    }

    /**
     * Get specific Mandiri questions by their IDs
     * Used to fetch previously generated random questions to prevent refresh exploitation
     *
     * @return \Illuminate\Http\Response
     */
    public function getByIds(Request $request)
    {
        $request->validate([
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'min:1'],
            'modul_id' => ['required', 'integer'],
        ]);

        $questionIds = $request->question_ids;
        $modulId = $request->modul_id;

        try {
            $questions = SoalMandiri::where('modul_id', $modulId)
                ->whereIn('id', $questionIds)
                ->get();

            // Preserve the order from the request
            $orderedQuestions = collect($questionIds)->map(function ($id) use ($questions) {
                return $questions->firstWhere('id', $id);
            })->filter(); // Remove null values

            return response()->json([
                'message' => 'success',
                'all_soal' => $orderedQuestions->values(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch questions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoalMandiri $soal_Mandiri)
    {
        //
    }
}
