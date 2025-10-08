<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPraktikan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pesan', 'asisten_id', 'modul_id', 'praktikan_id', 'rating_praktikum', 'rating_asisten',
    ];

    /**
     * Get the praktikan that this laporan belongs to.
     */
    public function praktikan(): BelongsTo
    {
        return $this->belongsTo(Praktikan::class);
    }

    /**
     * Get the asisten that this laporan belongs to.
     */
    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Asisten::class);
    }

    /**
     * Get the modul that this laporan belongs to.
     */
    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    /**
     * Get the nilai for this laporan if it exists.
     */
    public function nilai()
    {
        return Nilai::where('praktikan_id', $this->praktikan_id)
            ->where('modul_id', $this->modul_id)
            ->where('asisten_id', $this->asisten_id)
            ->first();
    }
}
