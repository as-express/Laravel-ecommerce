<?php

namespace App\Services;

use App\Exceptions\ErrorException;
use App\Http\Resources\ProductResource;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct($data, $image)
    {
        $isExist = Product::where('title', $data['title'])->first();
        if ($isExist) {
            throw new ErrorException('Product already exists', 409);
        }

        $url =  Storage::disk('s3')->put('product', $image);
        $data['image'] = $url;

        $product = Product::create($data);
        return [
            'message' => 'Product created successfully',
            'product' => new ProductResource($product),
        ];
    }

    public function getProducts()
    {
        return ProductResource::collection(Product::all());
    }

    public function getProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new ErrorException('Product not found', 404);
        }

        return new ProductResource($product);
    }

    public function favoriteProduct($userId, $productId)
    {
        $product = $this->getByiId($productId);

        $user = User::find($userId);
        if (!$user) {
            throw new ErrorException('User not found', 404);
        }
        if ($user->favoriteProducts()->where('product_id', $productId)->exists()) {
            $user->favoriteProducts()->detach($productId);
            return new ProductResource($product);
        }

        $user->favoriteProducts()->attach($productId);
        return new ProductResource($product);
    }

    public function addToCart($userId, $productId, $qty, $price)
    {
        // Проверка существования товара
        $this->getByiId($productId);

        // Проверка пользователя
        $user = User::find($userId);
        if (!$user) {
            throw new ErrorException('User not found', 404);
        }

        // Получение корзины
        $card = Card::where('user_id', $userId)->first();
        if (!$card) {
            throw new ErrorException('Card is not found', 404);
        }

        // Проверка, есть ли уже такой товар в корзине
        $item = CardItem::where('card_id', $card->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            // Если уже есть — обновляем количество и цену
            $item->quantity += $qty;
            $card->total_price + $price;
            $card->save();
            $item->save();
        } else {
            CardItem::create([
                'card_id' => $card->id,
                'product_id' => $productId,
                'quantity' => $qty,
                'price' => $price,
            ]);
        }

        return true;
    }
































    public function updateProduct($id, $data, $image)
    {
        $product = $this->getByiId($id);
        if ($image) {
            $url =  Storage::disk('s3')->put('product', $image);
            $data['image'] = $url;
        }
        $product->update($data);

        return [
            'message' => 'Product updated successfully',
            'product' => new ProductResource($product),
        ];
    }

    public function deleteProduct($id)
    {
        $product = $this->getByiId($id);
        $product->delete();

        return [
            'message' => 'Product deleted successfully',
        ];
    }

    private function getByiId($id)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new ErrorException('Product not found', 404);
        }

        return $product;
    }
}
