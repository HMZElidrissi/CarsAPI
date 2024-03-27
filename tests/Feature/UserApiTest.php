<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_users()
    {
        $user = User::factory()->create([
            'email' => 'test.admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user->createToken('authToken')->plainTextToken;

        $response = $this->actingAs($user)->getJson('/backend/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_create_user()
    {
        $user = User::factory()->create([
            'email' => 'test.admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user->createToken('authToken')->plainTextToken;

        $userData = [
            'name' => 'CREATE USER',
            'email' => 'create@create.com',
            'password' => 'password',
            'role' => 'user',
        ];

        $response = $this->actingAs($user)->postJson('/backend/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_update_user()
    {
        $user = User::factory()->create([
            'email' => 'update@update.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user->createToken('authToken')->plainTextToken;

        $userData = [
            'name' => 'UPDATE USER',
            'email' => 'updated@updated.com',
            'role' => 'admin',
        ];

        $response = $this->actingAs($user)->putJson('/backend/users/'.$user->id, $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'UPDATE USER',
            'email' => 'updated@updated.com',
            'role' => 'admin',
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'UPDATE USER',
            'email' => 'update@update.com',
            'role' => 'user',
        ]);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create([
            'email' => 'delete@delete.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user->createToken('authToken')->plainTextToken;

        $response = $this->actingAs($user)->deleteJson('/backend/users/'.$user->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'delete@delete.com',
            'role' => 'admin',
        ]);
    }
}
