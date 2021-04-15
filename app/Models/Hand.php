<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_play',
        'user_score',
        'computer_play',
        'computer_score',
        'user_won'
    ];
}
