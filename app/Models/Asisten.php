<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Asisten extends Authenticatable
{
    use HasFactory;

    protected $guard = 'asisten';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'kode', 'password', 'role_id', 'deskripsi', 'nomor_telepon', 'id_line', 'instagram',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the role that this asisten belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all feedback received by this asisten.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get all laporan praktikan handled by this asisten.
     */
    public function laporanPraktikan(): HasMany
    {
        return $this->hasMany(LaporanPraktikan::class);
    }

    /**
     * Get all nilai given by this asisten.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Get all jadwal jaga for this asisten.
     */
    public function jadwalJaga(): HasMany
    {
        return $this->hasMany(JadwalJaga::class);
    }

    /**
     * Get all polling results for this asisten.
     */
    public function pollingResults(): HasMany
    {
        return $this->hasMany(Polling::class);
    }
}
