<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface CartRepositoryInterface
{
    public function addToCart(Authenticatable|User $user, array $product): Cart;

    public function removeFromCart(Authenticatable|User $user, Product $product): Cart;

    public function userCart(Authenticatable|User $user): Cart;
}
