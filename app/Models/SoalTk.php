<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalTk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pertanyaan',
        'modul_id',
        'jawaban_benar',
        'jawaban_salah1',
        'jawaban_salah2',
        'jawaban_salah3',
    ];

    public function comments()
    {
        return $this->morphMany(SoalComment::class, 'soal', 'tipe_soal', 'soal_id');
    }
}
