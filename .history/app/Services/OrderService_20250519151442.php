<?php

namespace App\Services;

use Ap\Services\PromoService;
use App\Models\Card;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    // protected $promoService;

    // public function __construct(PromoService $promoService)
    // {
    // $this->promoService = $promoService;
    // }

    public function create($userId, $data)
    {
        $card = Card::where('user_id', $userId)->first();
        $order = Order::create([
            'user_id' => $userId,
            'address' => $data['address'],
        ]);

        $card->items->map(function ($item) use ($item) {
            OrderItem::create(
                [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                ]
            );
        });
    }
}
