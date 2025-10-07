<?php

namespace App\Http\Controllers;

use App\Enums\TipeSoal;
use App\Models\JawabanSnapshot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JawabanSnapshotController extends Controller
{
    // GET /api/jawaban-snapshot?praktikan_id=&modul_id=&tipe_soal=
    public function index(Request $req)
    {
        $data = JawabanSnapshot::query()
            ->when($req->praktikan_id, fn ($q) => $q->where('praktikan_id', $req->praktikan_id))
            ->when($req->modul_id, fn ($q) => $q->where('modul_id', $req->modul_id))
            ->when($req->tipe_soal, fn ($q) => $q->where('tipe_soal', $req->tipe_soal))
            ->get();

        return response()->json($data);
    }

    // POST /praktikan/autosave
    public function store(Request $req)
    {
        $validated = $req->validate([
            'praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'tipe_soal' => ['required', Rule::enum(TipeSoal::class)],
            'jawaban' => ['required', 'array'],
        ]);

        $snapshot = JawabanSnapshot::updateOrCreate(
            [
                'praktikan_id' => $validated['praktikan_id'],
                'modul_id' => $validated['modul_id'],
                'tipe_soal' => $validated['tipe_soal'],
            ],
            [
                'jawaban' => $validated['jawaban'],
            ]
        );

        return response()->json(['success' => true, 'data' => $snapshot]);
    }

    // POST /api/jawaban-snapshot/bulk-upsert
    // body: { items: [{praktikan_id, modul_id, tipe_soal, jawaban}, ...] }
    public function bulkUpsert(Request $req)
    {
        $validated = $req->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'items.*.modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'items.*.tipe_soal' => ['required', Rule::enum(TipeSoal::class)],
            'items.*.jawaban' => ['required', 'array'],
        ]);

        $rows = [];
        foreach ($validated['items'] as $item) {
            $rows[] = [
                'praktikan_id' => $item['praktikan_id'],
                'modul_id' => $item['modul_id'],
                'tipe_soal' => $item['tipe_soal'],
                'jawaban' => json_encode($item['jawaban']),
            ];
        }

        // Efficient conflict handling
        JawabanSnapshot::upsert(
            $rows,
            uniqueBy: ['praktikan_id', 'modul_id', 'tipe_soal'],
            update: ['jawaban', 'updated_at']
        );

        return response()->json(['success' => true]);
    }

    // DELETE /api/jawaban-snapshot/{id}
    public function destroy(JawabanSnapshot $jawabanSnapshot)
    {
        $jawabanSnapshot->delete();

        return response()->json(['success' => true]);
    }

    // DELETE /praktikan/autosave/clear
    // Clears all autosaved answers for a praktikan and modul
    public function clearPraktikanAnswers(Request $request)
    {
        $validated = $request->validate([
            'praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'tipe_soal' => ['nullable', Rule::enum(TipeSoal::class)],
        ]);

        $query = JawabanSnapshot::where('praktikan_id', $validated['praktikan_id'])
            ->where('modul_id', $validated['modul_id']);

        if (isset($validated['tipe_soal'])) {
            $query->where('tipe_soal', $validated['tipe_soal']);
        }

        $deleted = $query->delete();

        return response()->json([
            'success' => true,
            'deleted_count' => $deleted,
        ]);
    }
}
