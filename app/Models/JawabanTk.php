<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanTk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'praktikan_id', 'soal_id', 'modul_id', 'jawaban',
    ];

    /**
     * Get the soal that this jawaban belongs to.
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(SoalTk::class, 'soal_id');
    }

    /**
     * Get the praktikan that this jawaban belongs to.
     */
    public function praktikan(): BelongsTo
    {
        return $this->belongsTo(Praktikan::class);
    }

    /**
     * Get the modul that this jawaban belongs to.
     */
    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }
}
