<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modul extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul', 'deskripsi', 'isi', 'isEnglish', 'isUnlocked',
    ];

    /**
     * Get all soal TP for this modul.
     */
    public function soalTp(): HasMany
    {
        return $this->hasMany(SoalTp::class);
    }

    /**
     * Get all soal TA for this modul.
     */
    public function soalTa(): HasMany
    {
        return $this->hasMany(SoalTa::class);
    }

    /**
     * Get all soal TK for this modul.
     */
    public function soalTk(): HasMany
    {
        return $this->hasMany(SoalTk::class);
    }

    /**
     * Get all soal jurnal for this modul.
     */
    public function soalJurnal(): HasMany
    {
        return $this->hasMany(SoalJurnal::class);
    }

    /**
     * Get all soal mandiri for this modul.
     */
    public function soalMandiri(): HasMany
    {
        return $this->hasMany(SoalMandiri::class);
    }

    /**
     * Get all soal FITB for this modul.
     */
    public function soalFitb(): HasMany
    {
        return $this->hasMany(SoalFitb::class);
    }

    /**
     * Get all laporan praktikan for this modul.
     */
    public function laporanPraktikan(): HasMany
    {
        return $this->hasMany(LaporanPraktikan::class);
    }

    /**
     * Get all nilai for this modul.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}
