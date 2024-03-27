<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_cars()
    {
        $user = User::factory()->create([
            'email' => 'test.cars@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->createToken('authToken')->plainTextToken;

        $response = $this->actingAs($user)->getJson('/backend/cars');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'marque',
                    'modele',
                    'annee',
                    'kilometrage',
                    'prix',
                    'puissance',
                    'motorisation',
                    'carburant',
                    'options',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_estimate_price()
    {
        $user = User::factory()->create([
            'email' => 'test.price@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->createToken('authToken')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/backend/cars/estimate-price', [
            'marque' => 'Renault',
            'modele' => 'Clio',
            'annee' => 2015,
            'kilometrage' => 100000,
            'puissance' => 5,
            'motorisation' => 'Essence',
            'carburant' => 'Sans plomb',
            'options' => 'Climatisation, ABS, Airbag',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'estimated_price',
            ]);
    }
}
