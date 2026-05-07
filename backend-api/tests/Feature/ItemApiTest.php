<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
    }

    public function test_can_get_all_items()
    {
        $user = User::factory()->create();
        Item::create([
            'seller_id' => $user->id,
            'category_id' => 1,
            'title' => 'Test Item',
            'price' => 25.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/items');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_single_item()
    {
        $user = User::factory()->create();
        $item = Item::create([
            'seller_id' => $user->id,
            'category_id' => 1,
            'title' => 'Single Item',
            'price' => 30.00,
            'condition' => 'good',
            'status' => 'available',
        ]);

        $response = $this->getJson("/api/items/{$item->id}");

        $response->assertStatus(200)
            ->assertJson(['title' => 'Single Item']);
    }

    public function test_can_create_item()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/items', [
                'title' => 'New Item',
                'description' => 'Test description',
                'price' => 45.00,
                'condition' => 'new',
                'category_id' => 1,
            ]);

        $response->assertStatus(201)
            ->assertJson(['title' => 'New Item']);
    }

    public function test_can_update_own_item()
    {
        $user = User::factory()->create();
        $item = Item::create([
            'seller_id' => $user->id,
            'category_id' => 1,
            'title' => 'Original Title',
            'price' => 20.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/items/{$item->id}", [
                'title' => 'Updated Title',
                'price' => 25.00,
            ]);

        $response->assertStatus(200)
            ->assertJson(['title' => 'Updated Title']);
    }

    public function test_cannot_update_other_user_item()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $item = Item::create([
            'seller_id' => $user1->id,
            'category_id' => 1,
            'title' => 'Owner Item',
            'price' => 20.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->actingAs($user2, 'sanctum')
            ->putJson("/api/items/{$item->id}", [
                'title' => 'Hacked Title',
            ]);

        $response->assertStatus(403);
    }

    public function test_can_delete_own_item()
    {
        $user = User::factory()->create();
        $item = Item::create([
            'seller_id' => $user->id,
            'category_id' => 1,
            'title' => 'Delete Me',
            'price' => 20.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/items/{$item->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_can_get_carbon_savings()
    {
        $user = User::factory()->create();
        $item = Item::create([
            'seller_id' => $user->id,
            'category_id' => 1,
            'title' => 'Eco Item',
            'price' => 30.00,
            'condition' => 'new',
            'status' => 'available',
            'carbon_savings' => 5.5,
        ]);

        $response = $this->getJson("/api/items/{$item->id}/carbon-savings");

        $response->assertStatus(200)
            ->assertJson(['carbon_savings' => 5.5]);
    }
}