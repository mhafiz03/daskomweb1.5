<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\SoalTa;
use Illuminate\Http\Request;

class SoalTaController extends Controller
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
            'pertanyaan' => 'required|unique:soal_tas|string',
            'modul_id' => 'required',
            'jawaban_benar' => 'required',
            'jawaban_salah1' => 'required',
            'jawaban_salah2' => 'required',
            'jawaban_salah3' => 'required',
        ]);

        if ($request->jawaban_benar === $request->jawaban_salah1 ||
            $request->jawaban_benar === $request->jawaban_salah2 ||
            $request->jawaban_benar === $request->jawaban_salah3 ||
            $request->jawaban_salah1 === $request->jawaban_salah2 ||
            $request->jawaban_salah1 === $request->jawaban_salah3 ||
            $request->jawaban_salah2 === $request->jawaban_salah3) {

            return '{"message": "Tidak boleh ada jawaban yang sama"}';
        }

        SoalTa::create([
            'pertanyaan' => $request->pertanyaan,
            'modul_id' => $request->modul_id,
            'jawaban_benar' => $request->jawaban_benar,
            'jawaban_salah1' => $request->jawaban_salah1,
            'jawaban_salah2' => $request->jawaban_salah2,
            'jawaban_salah3' => $request->jawaban_salah3,
        ]);

        $id = SoalTa::where('pertanyaan', $request->pertanyaan)->first()->id;

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

            $all_soal = SoalTa::where('modul_id', $modul_id)->get();

        } else {

            $all_soal = SoalTa::where('modul_id', $modul_id)->inRandomOrder()->take(10)->get();
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
    public function edit(SoalTa $soal_Ta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\SoalTa  $soal_Ta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'modul_id' => 'required',
            'jawaban_benar' => 'required|string',
            'jawaban_salah1' => 'required|string',
            'jawaban_salah2' => 'required|string',
            'jawaban_salah3' => 'required|string',
        ]);

        if ($request->pertanyaan != $request->oldPertanyaan) {
            foreach (SoalTa::all() as $soal => $value) {
                if ($value->pertanyaan === $request->pertanyaan) {
                    return '{"message": "Soal '.$request->pertanyaan.' sudah terdaftar"}';
                }
            }
        }

        $soal = SoalTa::find($request->id);
        $soal->pertanyaan = $request->pertanyaan;
        $soal->jawaban_benar = $request->jawaban_benar;
        $soal->jawaban_salah1 = $request->jawaban_salah1;
        $soal->jawaban_salah2 = $request->jawaban_salah2;
        $soal->jawaban_salah3 = $request->jawaban_salah3;
        $soal->modul_id = $request->modul_id;
        $soal->save();

        $soal->id = $request->id;
        $soal->pertanyaan = $request->pertanyaan;
        $soal->jawaban_benar = $request->jawaban_benar;
        $soal->jawaban_salah1 = $request->jawaban_salah1;
        $soal->jawaban_salah2 = $request->jawaban_salah2;
        $soal->jawaban_salah3 = $request->jawaban_salah3;
        $soal->modul_id = $request->modul_id;

        return response()->json([
            'message' => 'success',
            'soal' => $soal,
        ], 200);
    }

    /**
     * Get specific TA questions by their IDs
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
            $questions = SoalTa::where('modul_id', $modulId)
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
    public function destroy(SoalTa $soal_Ta)
    {
        //
    }
}
