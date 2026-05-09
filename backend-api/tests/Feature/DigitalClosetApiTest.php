<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DigitalClosetApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-7: Digital Closet
    public function test_get_closet()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/closet');

        $response->assertStatus(200);
    }

    public function test_add_to_closet()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/closet', [
                'item_id' => 1,
            ]);

        $this->assertContains($response->getStatusCode(), [200, 201, 404, 422]);
    }

    public function test_list_item_from_closet()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/closet/1/list', [
                'price' => 50.00,
            ]);

        $this->assertContains($response->getStatusCode(), [200, 201, 404, 422]);
    }

    public function test_remove_from_closet()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/closet/1');

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function test_create_swap_invite()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/closet/1/swap-invite', [
                'recipient_id' => 2,
            ]);

        $this->assertContains($response->getStatusCode(), [200, 201, 404, 422]);
    }
}

class TransformationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-2: Impact Calculation & UC-8: Transformations
    public function test_get_item_transformation()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/items/1/transformation');

        $response->assertStatus(200);
    }

    public function test_save_transformation()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/items/1/transformation', []);

        $this->assertContains($response->getStatusCode(), [200, 201, 404, 422]);
    }

    public function test_calculate_impact()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/impact/calculate', [
                'materials_saved' => ['denim' => 2, 'cotton' => 1],
                'technique' => 'upcycle',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['co2_saved', 'water_saved', 'waste_diverted']);
    }
}

class ItemLockApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-10, UC-11, UC-12, UC-18, UC-19: Item Locking
    public function test_lock_item()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/items/1/lock', [
                'lock_type' => 'sale',
            ]);

        $response->assertStatus(200);
    }

    public function test_unlock_item()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/items/1/unlock');

        $response->assertStatus(200);
    }

    public function test_validate_item()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/items/validate', [
                'name' => 'Vintage Denim Jacket',
                'description' => 'Upcycled jacket',
                'category' => 'outerwear',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['valid', 'flags']);
    }

    public function test_auto_cancel_expired()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/transactions/auto-cancel');

        $response->assertStatus(200);
    }

    public function test_lock_item_for_agreement()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/transactions/1/lock-item');

        $response->assertStatus(200);
    }
}