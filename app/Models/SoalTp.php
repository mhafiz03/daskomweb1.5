<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalTp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'soal',
        'modul_id',
        'isEssay',
        'isProgram',
    ];

    public function comments()
    {
        return $this->morphMany(SoalComment::class, 'soal', 'tipe_soal', 'soal_id');
    }

    /**
     * Get the modul that owns the SoalTp.
     */
    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }
}
