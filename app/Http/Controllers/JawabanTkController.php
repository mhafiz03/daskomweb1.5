<?php

namespace App\Http\Controllers;

use App\Models\JawabanTk;
use App\Models\SoalTk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JawabanTkController extends Controller
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
        $payload = $request->all();
        if (! is_array($payload) || count($payload) === 0) {
            return response()->json(['message' => 'Tidak ada jawaban'], 422);
        }

        $praktikanId = $payload[0]['praktikan_id'] ?? null;
        $modulId = $payload[0]['modul_id'] ?? null;

        if (! $praktikanId || ! $modulId) {
            return response()->json(['message' => 'Praktikan/modul missing'], 422);
        }

        // Pull official TK questions for this modul
        $soalList = SoalTk::where('modul_id', $modulId)->pluck('jawaban_benar', 'id'); // [soal_id => jawaban_benar]
        $maxSoal = min($soalList->count(), 10); // Max 10 soal
        if ($maxSoal === 0) {
            return response()->json(['message' => 'no TK questions found for this modul'], 409);
        }

        $nilaiTk = 0;

        try {
            DB::transaction(function () use ($praktikanId, $modulId, $payload, $soalList, $maxSoal, &$nilaiTk) {
                // Delete previous answers
                JawabanTk::where('praktikan_id', $praktikanId)
                    ->where('modul_id', $modulId)
                    ->delete();

                $correct = 0;

                foreach ($payload as $row) {
                    $soalId = $row['soal_id'] ?? null;
                    $jaw = ($row['jawaban'] ?? '') === '' ? '-' : $row['jawaban'];

                    // Insert answer
                    JawabanTk::create([
                        'praktikan_id' => $praktikanId,
                        'modul_id' => $modulId,
                        'soal_id' => $soalId,
                        'jawaban' => $jaw,
                    ]);

                    // Count correct if soal_id valid
                    if ($soalId && isset($soalList[$soalId]) && $jaw === $soalList[$soalId]) {
                        $correct++;
                    }
                }

                // Grade with max 10 soal
                $nilaiTk = (int) round($correct * 100 / $maxSoal);
            });

            return response()->json(['message' => 'success', 'nilaiTk' => $nilaiTk], 200);

        } catch (\Throwable $e) {
            // Log error for debugging
            Log::error('TK grading failed', ['err' => $e->getMessage()]);

            return response()->json(['message' => 'grading failed'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanTk $jawaban_Tk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanTk $jawaban_Tk)
    {
        //
    }
}
