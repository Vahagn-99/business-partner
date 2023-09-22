<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_user_can_list_categories()
    {
        Category::factory(3)->create();
        $response = $this->json('get', '/api/v1/categories');
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_get_category()
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/v1/categories/{$category->getKey()}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $category->getKey()]]);
    }

    public function test_user_can_create_parent_category()
    {
        $categoryData = [
            'name' => 'New Category',
            'parent_id' => null, // Опционально, если это родительская категория
        ];

        $response = $this->post('/api/v1/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'New Category']]);
    }

    public function test_user_can_create_subcategory_category_()
    {
        $parent = Category::factory()->create(['level' => 8]);

        $categoryData = [
            'name' => 'New Category',
            'parent_id' => $parent->getKey(), // Опционально, если это родительская категория
        ];

        $response = $this->post('/api/v1/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'New Category'
                    , 'level' => 9
                ]]);
    }

    public function test_user_cant_create_subcategory_category_when_limit_extended()
    {
        $parent = Category::factory()->create(['level' => CategoryRepositoryInterface::CATEGORY_LIMIT]);

        $categoryData = [
            'name' => 'New Category',
            'parent_id' => $parent->getKey()
        ];

        $response = $this->post('/api/v1/categories', $categoryData);

        $response->assertStatus(419);
    }

    public function test_user_can_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'name before update'
        ]);

        $categoryData = [
            'name' => 'name after update',
            'id' => $category->getKey()
        ];

        $response = $this->post("/api/v1/categories", $categoryData);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'name after update']]);
    }

    public function test_user_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete("/api/v1/categories/{$category->getKey()}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->getKey()]);
    }
}

