<?php

namespace App\Http\Controllers;

use App\Enums\TipeSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class JawabanSnapshotController extends Controller
{
    /**
     * The cache store to use for storing snapshots.
     */
    private string $cacheStore = 'redis';

    /**
     * Generate a cache key for a jawaban snapshot.
     */
    private function getCacheKey(int $praktikanId, int $modulId, string $tipeSoal): string
    {
        return "jawaban_snapshot:{$praktikanId}:{$modulId}:{$tipeSoal}";
    }

    /**
     * Generate a pattern for searching cache keys.
     */
    private function getCachePattern(int $praktikanId, int $modulId, ?string $tipeSoal = null): string
    {
        if ($tipeSoal) {
            return "jawaban_snapshot:{$praktikanId}:{$modulId}:{$tipeSoal}";
        }

        return "jawaban_snapshot:{$praktikanId}:{$modulId}:*";
    }

    /**
     * Get Redis keys matching a pattern.
     * Note: This is a simplified implementation for development.
     * In production, consider using SCAN for large datasets to avoid blocking.
     */
    private function getRedisKeys(string $pattern): array
    {
        try {
            $redis = Redis::connection('cache');
            $keys = $redis->keys($pattern);

            // Handle the prefix if it exists
            $prefix = config('database.redis.options.prefix', '');
            if ($prefix && ! empty($keys)) {
                $keys = array_map(function ($key) use ($prefix) {
                    return str_starts_with($key, $prefix) ? substr($key, strlen($prefix)) : $key;
                }, $keys);
            }

            return $keys;
        } catch (\Exception $e) {
            // Fallback: return empty array if Redis search fails
            // In production, you might want to log this error
            return [];
        }
    }

    // GET /api/jawaban-snapshot?praktikan_id=&modul_id=&tipe_soal=
    public function index(Request $req)
    {
        $praktikanId = $req->praktikan_id;
        $modulId = $req->modul_id;
        $tipeSoal = $req->tipe_soal;

        $data = [];
        $cache = Cache::store($this->cacheStore);

        if ($praktikanId && $modulId && $tipeSoal) {
            // Get specific snapshot
            $key = $this->getCacheKey($praktikanId, $modulId, $tipeSoal);
            $snapshot = $cache->get($key);
            if ($snapshot) {
                $data[] = $snapshot;
            }
        } elseif ($praktikanId && $modulId) {
            // Get all snapshots for praktikan and modul
            $pattern = $this->getCachePattern($praktikanId, $modulId);
            $keys = $this->getRedisKeys($pattern);

            foreach ($keys as $key) {
                $snapshot = $cache->get($key);
                if ($snapshot) {
                    $data[] = $snapshot;
                }
            }
        } elseif ($praktikanId) {
            // Get all snapshots for praktikan
            $pattern = "jawaban_snapshot:{$praktikanId}:*";
            $keys = $this->getRedisKeys($pattern);

            foreach ($keys as $key) {
                $snapshot = $cache->get($key);
                if ($snapshot) {
                    $data[] = $snapshot;
                }
            }
        } else {
            // Get all snapshots (might be expensive, consider limiting)
            $pattern = 'jawaban_snapshot:*';
            $keys = $this->getRedisKeys($pattern);

            foreach ($keys as $key) {
                $snapshot = $cache->get($key);
                if ($snapshot) {
                    $data[] = $snapshot;
                }
            }
        }

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

        $cache = Cache::store($this->cacheStore);
        $key = $this->getCacheKey(
            $validated['praktikan_id'],
            $validated['modul_id'],
            $validated['tipe_soal']
        );

        $snapshot = [
            'praktikan_id' => $validated['praktikan_id'],
            'modul_id' => $validated['modul_id'],
            'tipe_soal' => $validated['tipe_soal'],
            'jawaban' => $validated['jawaban'],
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        // Check if exists to determine if this is an update
        $existing = $cache->get($key);
        if ($existing) {
            $snapshot['created_at'] = $existing['created_at'];
        }

        // Store in Redis with TTL of 30 days (configurable)
        $ttl = config('cache.snapshot_ttl', 60 * 60 * 24 * 30); // 30 days default
        $cache->put($key, $snapshot, $ttl);

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

        $cache = Cache::store($this->cacheStore);
        $ttl = config('cache.snapshot_ttl', 60 * 60 * 24 * 30); // 30 days default
        $currentTime = now()->toISOString();

        foreach ($validated['items'] as $item) {
            $key = $this->getCacheKey(
                $item['praktikan_id'],
                $item['modul_id'],
                $item['tipe_soal']
            );

            $snapshot = [
                'praktikan_id' => $item['praktikan_id'],
                'modul_id' => $item['modul_id'],
                'tipe_soal' => $item['tipe_soal'],
                'jawaban' => $item['jawaban'],
                'updated_at' => $currentTime,
            ];

            // Check if exists to preserve created_at
            $existing = $cache->get($key);
            if ($existing) {
                $snapshot['created_at'] = $existing['created_at'];
            } else {
                $snapshot['created_at'] = $currentTime;
            }

            $cache->put($key, $snapshot, $ttl);
        }

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

        $cache = Cache::store($this->cacheStore);
        $deletedCount = 0;

        if (isset($validated['tipe_soal'])) {
            // Clear specific tipe soal
            $key = $this->getCacheKey(
                $validated['praktikan_id'],
                $validated['modul_id'],
                $validated['tipe_soal']
            );

            if ($cache->forget($key)) {
                $deletedCount = 1;
            }
        } else {
            // Clear all tipe soal for the praktikan and modul
            foreach (TipeSoal::cases() as $tipe) {
                $key = $this->getCacheKey(
                    $validated['praktikan_id'],
                    $validated['modul_id'],
                    $tipe->value
                );

                if ($cache->forget($key)) {
                    $deletedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Store question IDs for a specific praktikan, modul, and soal type
     * This prevents refresh exploitation by locking in the questions on first visit
     */
    public function storeQuestionIds(Request $request)
    {
        $validated = $request->validate([
            'praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'tipe_soal' => ['required', Rule::in(['ta', 'tk', 'mandiri'])],
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'min:1'],
        ]);

        $cache = Cache::store($this->cacheStore);
        $tipeSoalQuestions = $validated['tipe_soal'].'_questions';

        $key = $this->getCacheKey(
            $validated['praktikan_id'],
            $validated['modul_id'],
            $tipeSoalQuestions
        );

        // Only store if not already exists (first visit only)
        if (! $cache->has($key)) {
            $snapshot = [
                'praktikan_id' => $validated['praktikan_id'],
                'modul_id' => $validated['modul_id'],
                'tipe_soal' => $tipeSoalQuestions,
                'question_ids' => $validated['question_ids'],
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ];

            // Store with TTL (Time To Live) - questions persist for the duration of praktikum
            $ttl = config('cache.ttl_jawaban_snapshot', 60 * 60 * 24); // 24 hours default
            $cache->put($key, $snapshot, $ttl);

            return response()->json([
                'success' => true,
                'message' => 'Question IDs stored successfully',
                'question_ids' => $validated['question_ids'],
            ]);
        }

        // Return existing question IDs if already stored
        $existingSnapshot = $cache->get($key);

        return response()->json([
            'success' => true,
            'message' => 'Question IDs already exist',
            'question_ids' => $existingSnapshot['question_ids'] ?? [],
        ]);
    }

    /**
     * Get stored question IDs for a specific praktikan, modul, and soal type
     */
    public function getQuestionIds(Request $request)
    {
        $validated = $request->validate([
            'praktikan_id' => ['required', 'integer', 'exists:praktikans,id'],
            'modul_id' => ['required', 'integer', 'exists:moduls,id'],
            'tipe_soal' => ['required', Rule::in(['ta', 'tk', 'mandiri'])],
        ]);

        $cache = Cache::store($this->cacheStore);
        $tipeSoalQuestions = $validated['tipe_soal'].'_questions';

        $key = $this->getCacheKey(
            $validated['praktikan_id'],
            $validated['modul_id'],
            $tipeSoalQuestions
        );

        $snapshot = $cache->get($key);

        if (! $snapshot) {
            return response()->json([
                'success' => true,
                'question_ids' => [],
                'has_stored_questions' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'question_ids' => $snapshot['question_ids'] ?? [],
            'has_stored_questions' => true,
            'created_at' => $snapshot['created_at'] ?? null,
        ]);
    }
}
