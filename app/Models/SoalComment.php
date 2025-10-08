<?php

namespace App\Models;

use App\Enums\TipeSoal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'soal_id',
        'tipe_soal',
        'praktikan_id',
        'comment',
    ];

    protected $casts = [
        'tipe_soal' => TipeSoal::class,
    ];

    // Polymorphic relationship to soal
    public function soal()
    {
        return $this->morphTo(__FUNCTION__, 'tipe_soal', 'soal_id');
    }

    // Praktikan relationship
    public function praktikan()
    {
        return $this->belongsTo(Praktikan::class);
    }
}
