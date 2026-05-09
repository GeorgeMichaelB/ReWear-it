<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SwapBundleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-15, UC-16, UC-17: Swap Bundle Management
    public function test_create_proposal()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/swap/proposal', []);

        $this->assertContains($response->getStatusCode(), [200, 422]);
    }

    public function test_calculate_topup()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/swap/calculate-topup', []);

        $this->assertContains($response->getStatusCode(), [200, 422]);
    }

    public function test_update_bundle()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/swap/bundle/update', []);

        $this->assertContains($response->getStatusCode(), [200, 404, 422]);
    }

    public function test_set_bargaining_thresholds()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/swap/bargaining/thresholds', [
                'auto_accept_threshold' => 95,
                'auto_decline_threshold' => 50,
            ]);

        $response->assertStatus(200);
    }

    public function test_check_offer_thresholds()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/swap/bargaining/check', [
                'offer_amount' => 90,
                'auto_accept' => 95,
                'auto_decline' => 50,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['offer', 'action', 'message']);
    }
}