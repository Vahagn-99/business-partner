<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CartRepository implements CartRepositoryInterface
{
    public function addToCart(Authenticatable|User $user, array $product): Cart
    {
        $cart = $this->getUserCart($user);
        $this->syncProduct($cart, $product);
        return $cart->load(['products']);
    }

    public function removeFromCart(Authenticatable|User $user, Product $product): Cart
    {
        $cart = $this->getUserCart($user);
        $this->removeProducts($cart, $product);
        return $cart->load(['products']);
    }

    private function getUserCart(Authenticatable|User $user): Cart
    {
        /** @var Cart */
        return $user->cart()->firstOrCreate();
    }

    private function syncProduct(Cart $cart, array $product): void
    {
        /** @var CartProduct $existingProduct */
        $existingProduct = $cart->products()->find($product['id']);
        if ($existingProduct) {
            // Увеличиваем количество продукта
            $existingProduct->update(['quantity' => $existingProduct->quantity + $product['quantity']]);
        } else {
            // Создаем новую запись для продукта
            $cart->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }
    }

    private function removeProducts(Cart $cart, Product $product): void
    {
        $cart->products()->detach($product);
    }

    public function userCart(User|Authenticatable $user): Cart
    {
        $cart = $user->cart()->firstOrCreate();
        return $cart->load(['products']);
    }
}
