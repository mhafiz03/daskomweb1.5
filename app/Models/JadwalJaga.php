<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalJaga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas_id', 'asisten_id',
    ];

    /**
     * Get the asisten that this jadwal belongs to.
     */
    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Asisten::class);
    }

    /**
     * Get the kelas that this jadwal belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
