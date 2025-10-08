<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Praktikan extends Authenticatable
{
    use HasFactory;

    protected $guard = 'praktikan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nim',
        'password',
        'kelas_id',
        'alamat',
        'nomor_telepon',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the kelas that this praktikan belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Get all laporan for this praktikan.
     */
    public function laporanPraktikan(): HasMany
    {
        return $this->hasMany(LaporanPraktikan::class);
    }

    /**
     * Get all nilai for this praktikan.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Get all feedback sent by this praktikan.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get all polling responses by this praktikan.
     */
    public function polling(): HasMany
    {
        return $this->hasMany(Polling::class);
    }
}
