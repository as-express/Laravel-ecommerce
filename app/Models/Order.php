<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'address'
    ];

    public function items()
    {
        return $this->belongsToMany(OrderItem::class, 'order_items')->withPivot('quantity')->withTimestamps();
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'payments')->withTimestamps();
    }
}
