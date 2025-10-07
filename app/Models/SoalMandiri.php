<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalMandiri extends Model
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
    ];

    public function comments()
    {
        return $this->morphMany(SoalComment::class, 'soal', 'soal_type', 'soal_id');
    }
}
