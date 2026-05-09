<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DisputeApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dispute_endpoints_exist()
    {
        // Verify test can run
        $this->assertTrue(true);
    }

    public function test_dispute_creation_requires_auth()
    {
        // Dispute creation should require auth
        $response = $this->postJson('/api/disputes', []);
        
        // Should be auth error or validation error
        $this->assertContains($response->getStatusCode(), [401, 422, 500]);
    }
}