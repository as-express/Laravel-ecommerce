<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use ErrorException;

class UserService
{
    public function getProfile($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new ErrorException('Usr not found', 404);
        }

        return $user;
    }

    public function getFavorites($userId)
    {
        $user = User::find($userId);
        $favorites = $user->favoriteProducts;

        return  $favorites;
    }

    public function getCard($userId)
    {
        $card = Card::with('items')->where('user_id', $userId)->first();
        if (!$card) {
            throw new ErrorException('Card not found', 404);
        }
        $card->productsCount = $card->items->sum(function ($item) {
            return $item->pivot->quantity;
        });
        $card->total_price = $card->items->sum(function ($item) {
            return $item->price * $item->pivot->quantity;
        });
        $card->items->each(function ($item) {
            $item->price = (float)$item->price;
        });

        return $card;
    }

    public function getOrders($userId)
    {
        $orders = Order::where('user_id', $userId)->get();
        $orders->each(function ($order) {
            $order->items->each(function ($item) {
                $item->product->price = (float)$item->product->price;
            });
        });

        return $orders;
    }
}
