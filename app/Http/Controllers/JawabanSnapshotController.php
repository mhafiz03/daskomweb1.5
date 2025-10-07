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

    // POST /api/jawaban-snapshot/store
    // body: { praktikan_id, soal_id, modul_id, tipe_soal, jawaban }
    public function store(Request $req)
    {
        $validated = $req->validate([
            'praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'soal_id' => ['required', 'integer'],
            'modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'tipe_soal' => ['required', Rule::enum(TipeSoal::class)],
            'jawaban' => ['required', 'array'],
        ]);

        $snapshot = JawabanSnapshot::updateOrCreate(
            [
                'praktikan_id' => $validated['praktikan_id'],
                'soal_id' => $validated['soal_id'],
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
    // body: { items: [{praktikan_id, soal_id, modul_id, tipe_soal, jawaban}, ...] }
    public function bulkUpsert(Request $req)
    {
        $validated = $req->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'items.*.soal_id' => ['required', 'integer'],
            'items.*.modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'items.*.tipe_soal' => ['required', Rule::enum(TipeSoal::class)],
            'items.*.jawaban' => ['required', 'array'],
        ]);

        $rows = [];
        foreach ($validated['items'] as $item) {
            $rows[] = [
                'praktikan_id' => $item['praktikan_id'],
                'soal_id' => $item['soal_id'],
                'modul_id' => $item['modul_id'],
                'tipe_soal' => $item['tipe_soal'],
                'jawaban' => json_encode($item['jawaban']),
            ];
        }

        // Efficient conflict handling
        JawabanSnapshot::upsert(
            $rows,
            uniqueBy: ['praktikan_id', 'soal_id', 'modul_id', 'tipe_soal'],
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
}
