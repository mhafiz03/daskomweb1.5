<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tp', 'ta', 'tk', 'jurnal', 'skill', 'diskon', 'rating', 'modul_id', 'asisten_id', 'kelas_id', 'praktikan_id',
    ];
}