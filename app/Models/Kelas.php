<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas', 'hari', 'shift', 'totalGroup',
    ];

    /**
     * Get all praktikan in this kelas.
     */
    public function praktikan(): HasMany
    {
        return $this->hasMany(Praktikan::class);
    }

    /**
     * Get all jadwal jaga for this kelas.
     */
    public function jadwalJaga(): HasMany
    {
        return $this->hasMany(JadwalJaga::class);
    }
}
