<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eltovary extends Model
{

    protected $table = 'eltovary';

    protected $fillable = [
        'tname',
        'kod',
        'date_p',
        'opisanie',
        'price',
        'kol_vo',
        'photo'
    ];

    protected $casts = [
        'date_p' => 'date',
        'price' => 'decimal:2'
    ];
}