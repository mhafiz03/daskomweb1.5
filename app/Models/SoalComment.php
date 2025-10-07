<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'soal_id',
        'soal_type',
        'praktikan_id',
        'comment'
    ];

    // Polymorphic relationship to soal
    public function soal()
    {
        return $this->morphTo(__FUNCTION__, 'soal_type', 'soal_id');
    }

    // Praktikan relationship
    public function praktikan()
    {
        return $this->belongsTo(Praktikan::class);
    }
}
