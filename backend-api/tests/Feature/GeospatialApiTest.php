<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class GeospatialApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-21: Geospatial - Find Nearby Users
    public function test_find_nearby_users()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/geospatial/nearby-users', [
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'radius_km' => 10,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['nearby_users', 'total_nearby', 'emissions_saved_kg']);
    }

    public function test_find_nearby_items()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/geospatial/nearby-items', [
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'radius_km' => 10,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['items', 'count']);
    }

    public function test_get_location_settings()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/geospatial/settings');

        $response->assertStatus(200);
    }

    public function test_set_location()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/geospatial/settings', [
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'city' => 'New York',
            ]);

        $response->assertStatus(200);
    }
}

class EscrowApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-22, UC-23, UC-27, UC-28: Escrow & Fees
    public function test_create_escrow()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/escrow/create', [
                'amount' => 100.00,
                'item_id' => 1,
                'buyer_id' => 2,
                'seller_id' => 3,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['escrow', 'vault_balance']);
    }

    public function test_release_funds()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/escrow/ESC-123/release', [
                'verified' => true,
            ]);

        $response->assertStatus(200);
    }

    public function test_dispute_resolution()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/escrow/ESC-123/dispute-resolution', [
                'resolution' => 'buyer_favor',
            ]);

        $response->assertStatus(200);
    }

    public function test_schedule_payout()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/escrow/ESC-123/payout', [
                'payout_schedule' => 'weekly',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['scheduled_date', 'status']);
    }

    public function test_calculate_platform_fee()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/fees/calculate', [
                'amount' => 100,
                'item_type' => 'standard',
            ]);

        $response->assertStatus(200);
    }

    public function test_convert_currency()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/currency/convert', [
                'amount' => 100,
                'from_currency' => 'USD',
                'to_currency' => 'EUR',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['converted_amount', 'exchange_rate']);
    }
}

class ShippingApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-24, UC-25, UC-26: Shipping & Returns
    public function test_generate_tracking()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/shipping/generate-label', [
                'from_postal' => '10001',
                'to_postal' => '90210',
                'weight_kg' => 2.5,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['tracking_number', 'label_url', 'shipping_cost']);
    }

    public function test_get_tracking_status()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/shipping/track/RW123456789');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'current_location', 'events']);
    }

    public function test_initiate_return()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/returns/initiate', [
                'order_id' => 'ORD-123',
                'reason' => 'not_as_described',
                'item_condition' => 'good',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['return_id', 'shipping_label']);
    }

    public function test_process_return()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/returns/RET-123/process', [
                'item_condition' => 'good',
                'refund_eligible' => true,
            ]);

        $response->assertStatus(200);
    }

    public function test_calculate_bundle_discount()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/discounts/bundle', [
                'item_prices' => [50, 60, 70],
                'seller_id' => 1,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['discount_rate', 'final_total', 'savings']);
    }
}

class DisputeApiExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-29: Extended Dispute Tests
    public function test_create_dispute()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/disputes', [
                'order_id' => 'ORD-123',
                'dispute_type' => 'not_received',
                'description' => 'Item never arrived',
            ]);

        $response->assertStatus(201);
    }

    public function test_get_admin_disputes()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/disputes');

        $response->assertStatus(200)
            ->assertJsonStructure(['disputes', 'total']);
    }

    public function test_resolve_dispute()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/disputes/DSP-123/resolve', [
                'resolution' => 'buyer_wins',
                'reason' => 'Item not received',
            ]);

        $response->assertStatus(200);
    }

    public function test_attach_chat_logs()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/disputes/DSP-123/attach-logs', [
                'chat_logs' => [['sender' => 'buyer', 'message' => 'Where is my item?']],
            ]);

        $response->assertStatus(200);
    }
}

class StyleBoardApiExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-30: Extended Style Board Tests
    public function test_get_public_boards()
    {
        $response = $this->getJson('/api/style-boards/public');

        $response->assertStatus(200)
            ->assertJsonStructure(['boards', 'total']);
    }

    public function test_get_board_details()
    {
        $response = $this->getJson('/api/style-boards/SB-001/details');

        $response->assertStatus(200)
            ->assertJsonStructure(['board', 'items']);
    }

    public function test_follow_board()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/style-boards/SB-001/follow');

        $response->assertStatus(200);
    }

    public function test_add_collaborator()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/style-boards/SB-001/collaborators', [
                'user_id' => 2,
            ]);

        $response->assertStatus(200);
    }

    public function test_get_followed_boards()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/style-boards/followed');

        $response->assertStatus(200);
    }

    public function test_update_board()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/style-boards/SB-001', [
                'name' => 'Updated Board',
                'is_public' => true,
            ]);

        $response->assertStatus(200);
    }

    public function test_delete_board()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/style-boards/SB-001');

        $response->assertStatus(200);
    }
}