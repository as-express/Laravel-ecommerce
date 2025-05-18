<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'title',
        'discount',
        'expires_at',
        'expired',
        'limit',
    ];
}
