<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_address()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/addresses', [
                'type' => 'shipping',
                'full_name' => 'John Doe',
                'phone' => '+1234567890',
                'address_line1' => '123 Main St',
                'city' => 'New York',
                'postal_code' => '10001',
                'country' => 'USA',
            ]);

        $response->assertStatus(201)
            ->assertJson(['city' => 'New York']);
    }

    public function test_can_get_own_addresses()
    {
        $user = User::factory()->create();
        Address::create(['user_id' => $user->id, 'type' => 'shipping', 'full_name' => 'Test', 'phone' => '123', 'address_line1' => '123 St', 'city' => 'Test', 'postal_code' => '123', 'country' => 'USA']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/addresses');

        $response->assertStatus(200);
    }

    public function test_can_update_own_address()
    {
        $user = User::factory()->create();
        $address = Address::create(['user_id' => $user->id, 'type' => 'shipping', 'full_name' => 'Old Name', 'phone' => '123', 'address_line1' => '123 St', 'city' => 'Old City', 'postal_code' => '123', 'country' => 'USA']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/addresses/{$address->id}", [
                'city' => 'New City',
            ]);

        $response->assertStatus(200)
            ->assertJson(['city' => 'New City']);
    }

    public function test_can_delete_own_address()
    {
        $user = User::factory()->create();
        $address = Address::create(['user_id' => $user->id, 'type' => 'shipping', 'full_name' => 'Delete Me', 'phone' => '123', 'address_line1' => '123 St', 'city' => 'Test', 'postal_code' => '123', 'country' => 'USA']);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/addresses/{$address->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }
}