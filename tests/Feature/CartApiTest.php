<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_cart()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        /** @var Cart $cart */
        $cart = $user->cart()->create();
        $products = Product::factory(3)->create();

        foreach ($products as $product) {
            $cart->products()->attach($product);
        }
        $response = $this->json('get', '/api/v1/cart');
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.products');
    }

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        $data = [
            'id' => $product->getKey()
            , 'quantity' => 2
        ];

        $response = $this->actingAs($user)->json('post', '/api/v1/cart', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'products' => [
                        [
                            'id' => $product->getKey()
                        ]
                    ]
                ]
            ]);
    }

    public function test_user_can_remove_product_from_cart()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        /** @var Cart $cart */
        $cart = $user->cart()->create();
        $product = Product::factory()->create();
        $cart->products()->attach($product->getKey(), ['quantity' => 1]);
        $response = $this->json('delete', '/api/v1/cart/' . $product->getKey());
        $response->assertStatus(204);
    }
}

