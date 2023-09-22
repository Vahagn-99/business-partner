<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_product_with_relations()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->getKey()
        ]);
        $image = Image::factory()->create(
            ['image_owner_id' => $product->getKey()]
        );
        $response = $this->get("/api/v1/products/{$product->getKey()}");
        $response->assertStatus(200)
            ->assertJson(['data' => [
                'name' => $product->name
                , 'category_id' => $category->getKey()
                , 'images' => [
                    [
                        'id' => $image->getKey()
                        , 'url' => $image->url
                    ]
                ]
            ]]);
    }

    public function test_user_can_get_list_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        Product::factory(100)->create([
            'category_id' => $category->getKey()
        ]);

        $response = $this->get("/api/v1/products");
        $response->assertStatus(200)
            ->assertJsonCount(100, 'data');
    }

    public function test_user_can_filter_list_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        Product::factory(100)->create([
            'category_id' => $category->getKey()
        ]);

        Product::factory(2)->create([
            'name' => 'find me'
            , 'category_id' => $category->getKey()
        ]);

        $filter = [
            'filter' => 'find me'
        ];

        $response = $this->json('get', "/api/v1/products", $filter);
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_user_with_role_admin_can_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        Sanctum::actingAs($user);

        $category = Category::factory()->create();

        $productData = [
            'name' => 'New Product',
            'price' => 19.99,
            'description' => 'Description of the product',
            'in_stock' => true,
            'category_id' => $category->getKey(),
            'images' => ['image1.jpg', 'image2.jpg'],
        ];

        $response = $this->json('post', '/api/v1/products', $productData);
        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'New Product']])
            ->assertJson(['data' => ['category_id' => $category->getKey()]]);
    }

    public function test_user_can_update_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        Sanctum::actingAs($user);

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $product = Product::factory()->create([
            'name' => 'product before update'
            , 'category_id' => $category1->getKey()
        ]);

        $productData = [
            'id' => $product->getKey(),
            'name' => 'Product after update',
            'price' => 19.99,
            'description' => 'Description of the product',
            'in_stock' => true,
            'category_id' => $category2->getKey(),
            'images' => ['image1.jpg', 'image2.jpg'],
        ];

        $response = $this->json('post', '/api/v1/products', $productData);
        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Product after update']])
            ->assertJson(['data' => ['category_id' => $category2->getKey()]]);
    }

    public function test_user_with_incorrect_role_cant_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        Sanctum::actingAs($user);

        $category = Category::factory()->create();

        $productData = [
            'name' => 'New Product',
            'price' => 19.99,
            'description' => 'Description of the product',
            'in_stock' => true,
            'category_id' => $category->getKey(),
            'images' => ['image1.jpg', 'image2.jpg'],
        ];

        $response = $this->json('post', '/api/v1/products', $productData);
        $response->assertStatus(403);
    }
}
