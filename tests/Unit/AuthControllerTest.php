<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    public function testRegister()
{
    $userData = [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => 'password123',
    ];

    $response = $this->json('POST', '/api/register', $userData);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'User Created ',
        ]);

    $this->assertDatabaseHas('users', [
        'name' => $userData['name'],
        'email' => $userData['email'],
    ]);
}

    public function testLogin()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
            ]);
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer ' . $token);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'logged out']);
    }
}
