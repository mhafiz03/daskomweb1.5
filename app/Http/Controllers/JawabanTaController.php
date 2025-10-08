<?php

namespace App\Http\Controllers;

use App\Models\JawabanTa;
use App\Models\SoalTa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JawabanTaController extends Controller
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

        // Pull official TA questions for this modul
        $soalList = SoalTa::where('modul_id', $modulId)->pluck('jawaban_benar', 'id'); // [soal_id => jawaban_benar]
        $maxSoal = min($soalList->count(), 10); // Max 10 soal
        if ($maxSoal === 0) {
            return response()->json(['message' => 'no TA questions found for this modul'], 409);
        }

        $nilaiTa = 0;

        try {
            DB::transaction(function () use ($praktikanId, $modulId, $payload, $soalList, $maxSoal, &$nilaiTa) {
                // Delete previous answers
                JawabanTa::where('praktikan_id', $praktikanId)
                    ->where('modul_id', $modulId)
                    ->delete();

                $correct = 0;

                foreach ($payload as $row) {
                    $soalId = $row['soal_id'] ?? null;
                    $jaw = ($row['jawaban'] ?? '') === '' ? '-' : $row['jawaban'];

                    // Insert answer
                    JawabanTa::create([
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
                $nilaiTa = (int) round($correct * 100 / $maxSoal);
            });

            return response()->json(['message' => 'success', 'nilaiTa' => $nilaiTa], 200);

        } catch (\Throwable $e) {
            // Log error for debugging
            Log::error('TA grading failed', ['err' => $e->getMessage()]);

            return response()->json(['message' => 'grading failed'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(JawabanTa $jawaban_Ta)
    {
        //
    }

    /**
     * Get TA answers with questions for a specific praktikan and modul
     *
     * @return \Illuminate\Http\Response
     */
    public function getAnswersWithQuestions(int $praktikan_id, int $modul_id)
    {
        try {
            $answers = JawabanTa::where('praktikan_id', $praktikan_id)
                ->where('modul_id', $modul_id)
                ->with(['soal' => function ($query) {
                    $query->select('id', 'pertanyaan', 'jawaban_benar', 'jawaban_salah1', 'jawaban_salah2', 'jawaban_salah3');
                }])
                ->get(['id', 'soal_id', 'jawaban', 'created_at']);

            // Transform the data to include question details
            $formattedAnswers = $answers->map(function ($answer) {
                if ($answer->soal) {
                    // Create array of all answer options
                    $options = [
                        $answer->soal->jawaban_benar,
                        $answer->soal->jawaban_salah1,
                        $answer->soal->jawaban_salah2,
                        $answer->soal->jawaban_salah3,
                    ];

                    return [
                        'id' => $answer->id,
                        'soal_id' => $answer->soal_id,
                        'pertanyaan' => $answer->soal->pertanyaan,
                        'jawaban_praktikan' => $answer->jawaban,
                        'jawaban_benar' => $answer->soal->jawaban_benar,
                        'is_correct' => $answer->jawaban === $answer->soal->jawaban_benar,
                        'all_options' => $options,
                        'submitted_at' => $answer->created_at,
                    ];
                }

                return null;
            })->filter();

            return response()->json([
                'message' => 'success',
                'data' => $formattedAnswers,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Failed to get TA answers with questions', [
                'praktikan_id' => $praktikan_id,
                'modul_id' => $modul_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to retrieve answers',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(JawabanTa $jawaban_Ta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JawabanTa $jawaban_Ta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(JawabanTa $jawaban_Ta)
    {
        //
    }
}
