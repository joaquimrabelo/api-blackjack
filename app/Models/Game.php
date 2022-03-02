<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'blackjack';

    protected $fillable = [
        'baralho',
        'game',
    ];
}
