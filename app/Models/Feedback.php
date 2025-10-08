<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asisten_id', 'praktikan_id', 'pesan', 'kelas_id', 'read',
    ];

    /**
     * Get the asisten that this feedback belongs to.
     */
    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Asisten::class);
    }

    /**
     * Get the praktikan that sent this feedback.
     */
    public function praktikan(): BelongsTo
    {
        return $this->belongsTo(Praktikan::class);
    }

    /**
     * Get the kelas that this feedback belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
