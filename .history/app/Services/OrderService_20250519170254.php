<?php

namespace App\Services;

use App\Services\PromoService;
use App\Models\Card;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    protected $promoService;

    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }


    public function create($userId, $data)
    {
        $itemsCount = 0;
        $promoDiscount = 0;

        $card = Card::where('user_id', $userId)->first();
        $order = Order::create([
            'user_id' => $userId,
            'address' => $data['address'],
        ]);

        $card->items->each(function ($item) use ($order, &$itemsCount) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->id,
                'quantity'   => $item->pivot->quantity,
                'price'      => $item->pivot->quantity * (float) $item->price,
            ]);
            $itemsCount += $item->pivot->quantity;
        });

        if (!empty($data['promo'])) {
            $promo = $this->promoService->searchPromo($data['promo']);
            $promoDiscount = $promo->discount;
        }

        $totalPrice = $card->items->map(function ($item) {
            return $item->pivot->quantity * (float) $item->price;
        })->sum();

        $order->total_products = $itemsCount;
        $order->discount = $promoDiscount;
        $order->total_price = $totalPrice * (1 - $promoDiscount / 100);
        $order->save();

        // $card->items()->detach(); 
        return $order;
    }

    public function getOrder($userId, $orderId)
    {
        $order = Order::where('user_id', $userId)->where('id', $orderId)->first();
        if (!$order) {
            throw new ErrorException('Order not found', 404);
        }
    }
}
