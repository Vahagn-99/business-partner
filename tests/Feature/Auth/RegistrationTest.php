<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret_password',
        ];

        $response = $this->post('/api/register', $userData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        $this->assertInstanceOf(User::class, User::query()->where('email', 'john@example.com')->first());
    }
}
