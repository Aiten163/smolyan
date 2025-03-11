<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Worker extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'second_name',
        'first_name',
        'middle_name',
        'birthday',
        'position',
    ];

}
