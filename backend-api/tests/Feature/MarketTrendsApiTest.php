<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MarketTrendsApiTest extends TestCase
{
    // UC-36: Market Trends
    public function test_get_material_trends()
    {
        $response = $this->getJson('/api/trends/materials?period=30_days');
        $response->assertStatus(200);
    }

    public function test_get_category_performance()
    {
        $response = $this->getJson('/api/trends/categories');
        $response->assertStatus(200);
    }

    public function test_get_price_recommendations()
    {
        $response = $this->getJson('/api/trends/pricing?category=tops&condition=good&material=cotton');
        $response->assertStatus(200);
    }

    public function test_get_seasonal_trends()
    {
        $response = $this->getJson('/api/trends/seasonal?season=spring');
        $response->assertStatus(200);
    }
}

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-37: Dynamic Commission
    public function test_set_commission_modifier()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/commission/set', []);

        $this->assertContains($response->getStatusCode(), [200, 201, 422]);
    }

    public function test_get_commission_modifiers()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/commission/modifiers');

        $response->assertStatus(200);
    }

    public function test_calculate_effective_fee()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/fees/effective?amount=100&category_id=1');

        $response->assertStatus(200);
    }

    // UC-38: Sustainability Audit
    public function test_get_sustainability_audit()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/audit/sustainability?period=30_days');

        $response->assertStatus(200);
    }

    public function test_export_audit_report()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/audit/export?format=json');

        $response->assertStatus(200);
    }

    // UC-39: Role-Based Access Control
    public function test_get_roles()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/roles');

        $response->assertStatus(200);
    }

    public function test_assign_role()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/roles/assign', []);

        $this->assertContains($response->getStatusCode(), [200, 422]);
    }

    public function test_check_permissions()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/roles/check/2');

        $response->assertStatus(200);
    }

    public function test_create_role()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/roles/create', []);

        $this->assertContains($response->getStatusCode(), [200, 201, 422]);
    }
}

class NewsletterApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-41: Newsletter Curation
    public function test_generate_weekly_newsletter()
    {
        $response = $this->getJson('/api/newsletter/generate');
        $response->assertStatus(200);
    }

    public function test_send_newsletter()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/newsletter/send', [
                'newsletter_id' => 'NL-2025-20',
                'test_only' => true,
            ]);

        $response->assertStatus(200);
    }

    public function test_get_subscribers()
    {
        $response = $this->getJson('/api/newsletter/subscribers');
        $response->assertStatus(200);
    }

    public function test_subscribe()
    {
        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'test@example.com',
            'preferences' => ['trending', 'weekly'],
        ]);

        $response->assertStatus(200);
    }

    public function test_get_past_newsletters()
    {
        $response = $this->getJson('/api/newsletter/past');
        $response->assertStatus(200);
    }
}

class DatabaseCleanupApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-42: Database Cleanup
    public function test_get_cleanup_status()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/cleanup/status');

        $response->assertStatus(200);
    }

    public function test_run_cleanup()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cleanup/run', [
                'type' => 'all',
            ]);

        $response->assertStatus(200);
    }

    public function test_archive_transactions()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cleanup/archive', [
                'older_than_days' => 90,
                'status' => 'completed',
            ]);

        $response->assertStatus(200);
    }

    public function test_get_archived_transactions()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/cleanup/archived');

        $response->assertStatus(200);
    }

    public function test_restore_transaction()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cleanup/archived/TXN-001/restore');

        $response->assertStatus(200);
    }

    public function test_cleanup_orphaned()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cleanup/orphaned');

        $response->assertStatus(200);
    }

    public function test_get_database_health()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/cleanup/health');

        $response->assertStatus(200);
    }
}