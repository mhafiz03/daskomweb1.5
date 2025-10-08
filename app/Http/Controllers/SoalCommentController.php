<?php

namespace App\Http\Controllers;

use App\Enums\TipeSoal;
use App\Models\SoalComment;
use Illuminate\Http\Request;

class SoalCommentController extends Controller
{
    public function showByModul($tipe_soal, $modul_id)
    {
        // Validate tipe_soal using the enum
        $tipeSoalEnum = TipeSoal::tryFrom($tipe_soal);
        if (! $tipeSoalEnum) {
            return response()->json(['error' => 'Invalid soal type'], 422);
        }

        $soalModel = TipeSoal::getModelClass($tipeSoalEnum);

        // Get all soal IDs for this modul_id
        $soalIds = $soalModel::where('modul_id', $modul_id)->pluck('id');

        // Get all comments for these soal IDs and this type
        $comments = SoalComment::where('tipe_soal', $tipe_soal)
            ->whereIn('soal_id', $soalIds)
            ->with('praktikan') // optional: eager load praktikan
            ->get();

        return response()->json(['success' => true, 'data' => $comments]);
    }

    public function store(Request $request, $praktikan_id, $tipe_soal, $soal_id)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Validate tipe_soal using the enum
        $tipeSoalEnum = TipeSoal::tryFrom($tipe_soal);
        if (! $tipeSoalEnum) {
            return response()->json(['error' => 'Invalid soal type'], 422);
        }

        $comment = SoalComment::create([
            'soal_id' => $soal_id,
            'tipe_soal' => $tipe_soal,
            'praktikan_id' => $praktikan_id,
            'comment' => $validated['comment'],
        ]);

        return response()->json(['success' => true, 'data' => $comment]);
    }
}
