<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'registrationPraktikan_activation', 'registrationAsisten_activation', 'tp_activation', 'tubes_activation', 'secretfeature_activation', 'runmod_activation', 'polling_activation',
    ];
}