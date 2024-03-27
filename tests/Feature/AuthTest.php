<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $userData = [
            'name' => 'Hamza EL IDRISSI',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/backend/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'message',
            ]);
    }

    public function test_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/backend/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'message',
            ]);
    }

    public function test_logout()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->createToken('authToken')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/backend/logout');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
