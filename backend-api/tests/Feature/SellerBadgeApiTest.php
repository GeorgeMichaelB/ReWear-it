<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SellerBadgeApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-1: Pro-Upcycler Badges
    public function test_get_seller_badges()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/seller/badges');

        $response->assertStatus(200)
            ->assertJsonStructure(['badges', 'available_badges']);
    }

    public function test_update_seller_badges()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/seller/badges', []);

        // Accept both 200 and 422 (validation error)
        $this->assertContains($response->getStatusCode(), [200, 422]);
    }

    public function test_verify_eco_credentials()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/seller/verify-eco', []);

        $this->assertContains($response->getStatusCode(), [200, 422]);
    }

    // UC-6: Trust Score
    public function test_get_trust_score()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/seller/trust-score');

        $response->assertStatus(200);
    }

    public function test_update_trust_score()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/seller/trust-score', []);

        $this->assertContains($response->getStatusCode(), [200, 422]);
    }
}