<?php

namespace App\Http\Controllers;

use App\Models\SoalComment;
use Illuminate\Http\Request;


class SoalCommentController extends Controller
{
    protected $soalModelMap = [
        'tp' => \App\Models\SoalTp::class,
        'ta' => \App\Models\SoalTa::class,
        'tk' => \App\Models\SoalTk::class,
        'jurnal' => \App\Models\SoalJurnal::class,
        'mandiri' => \App\Models\SoalMandiri::class,
        'fitb' => \App\Models\SoalFitb::class,
    ];

    public function showByModul($soal_type, $modul_id)
    {
        if (!isset($this->soalModelMap[$soal_type])) {
            return response()->json(['error' => 'Invalid soal type'], 422);
        }

        $soalModel = $this->soalModelMap[$soal_type];

        // Get all soal IDs for this modul_id
        $soalIds = $soalModel::where('modul_id', $modul_id)->pluck('id');

        // Get all comments for these soal IDs and this type
        $comments = SoalComment::where('soal_type', $soal_type)
            ->whereIn('soal_id', $soalIds)
            ->with('praktikan') // optional: eager load praktikan
            ->get();

        return response()->json(['success' => true, 'data' => $comments]);
    }

    public function store(Request $request, $praktikan_id, $soal_type, $soal_id)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        if (!isset($this->soalModelMap[$soal_type])) {
            return response()->json(['error' => 'Invalid soal type'], 422);
        }

        $comment = SoalComment::create([
            'soal_id'      => $soal_id,
            'soal_type'    => $soal_type,
            'praktikan_id' => $praktikan_id,
            'comment'      => $validated['comment'],
        ]);

        return response()->json(['success' => true, 'data' => $comment]);
    }
}
