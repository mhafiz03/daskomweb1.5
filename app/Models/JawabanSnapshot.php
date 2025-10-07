<?php

namespace App\Models;

use App\Enums\TipeSoal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanSnapshot extends Model
{
    protected $fillable = [
        'praktikan_id',
        'modul_id',
        'soal_id',
        'tipe_soal',
        'jawaban',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'tipe_soal' => TipeSoal::class,
    ];

    public function praktikan(): BelongsTo
    {
        return $this->belongsTo(Praktikan::class);
    }

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }
}
