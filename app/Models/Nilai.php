<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tp', 'ta', 'tk', 'jurnal', 'skill', 'diskon', 'rating', 'modul_id', 'asisten_id', 'kelas_id', 'praktikan_id',
    ];

    /**
     * Get the praktikan that this nilai belongs to.
     */
    public function praktikan(): BelongsTo
    {
        return $this->belongsTo(Praktikan::class);
    }

    /**
     * Get the asisten that gave this nilai.
     */
    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Asisten::class);
    }

    /**
     * Get the modul that this nilai belongs to.
     */
    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    /**
     * Get the kelas that this nilai belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
