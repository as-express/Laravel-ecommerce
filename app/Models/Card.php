<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'productsCount',
        'total_price',
        'discount',
        'total_products',
    ];

    public function items()
    {
        return $this->belongsToMany(Product::class, 'card_items')->withPivot('quantity')->withTimestamps();
    }
}
