<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-31: Live Drop Notifications
    public function test_create_drop()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/drops/create', [
                'seller_id' => 1,
                'title' => 'Summer Collection Drop',
                'items' => [
                    ['name' => 'Item 1', 'price' => 50],
                    ['name' => 'Item 2', 'price' => 35],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['drop', 'notifications_sent']);
    }

    public function test_get_active_drops()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/drops/active');

        $response->assertStatus(200)
            ->assertJsonStructure(['drops', 'total']);
    }

    public function test_follow_seller()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/sellers/follow', [
                'seller_id' => 2,
            ]);

        $response->assertStatus(200);
    }

    public function test_get_drop_subscriptions()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/drops/subscriptions');

        $response->assertStatus(200);
    }

    public function test_get_notifications()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure(['notifications', 'unread_count']);
    }

    public function test_mark_notification_read()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/notifications/read', [
                'notification_id' => 'notif-1',
            ]);

        $response->assertStatus(200);
    }
}

class AnalyticsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-32: Seller Analytics
    public function test_get_seller_analytics()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/analytics/seller?period=30_days');

        $response->assertStatus(200)
            ->assertJsonStructure(['sales', 'views', 'sustainability_impact']);
    }

    public function test_get_chart_data()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/analytics/charts?type=sales');

        $response->assertStatus(200)
            ->assertJsonStructure(['chart_type', 'data']);
    }

    // UC-40: System Health
    public function test_get_system_health()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/health');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'metrics', 'services']);
    }

    public function test_get_transaction_failures()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/health/failures');

        $response->assertStatus(200)
            ->assertJsonStructure(['total_failures', 'failure_rate']);
    }

    public function test_get_listing_latency()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/health/latency');

        $response->assertStatus(200)
            ->assertJsonStructure(['average_latency_ms', 'p95_latency_ms']);
    }
}

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-33: Nested Comments
    public function test_get_comments()
    {
        $response = $this->getJson('/api/items/1/comments');

        $response->assertStatus(200)
            ->assertJsonStructure(['comments', 'total_comments']);
    }

    public function test_add_comment()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/comments', [
                'item_id' => 1,
                'content' => 'Great item! Love the transformation.',
            ]);

        $response->assertStatus(201);
    }

    public function test_reply_to_comment()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/comments/1/reply', [
                'content' => 'Thanks!',
            ]);

        $response->assertStatus(201);
    }

    public function test_like_comment()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/comments/1/like');

        $response->assertStatus(200);
    }

    public function test_report_comment()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/comments/1/report', [
                'reason' => 'inappropriate',
            ]);

        $response->assertStatus(200);
    }

    public function test_delete_comment()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/comments/1');

        $response->assertStatus(200);
    }
}

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-34: Multi-stage Reporting
    public function test_create_report()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/reports', [
                'report_type' => 'harassment',
                'target_type' => 'user',
                'target_id' => 2,
                'description' => 'User is sending inappropriate messages',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['report', 'severity']);
    }

    public function test_get_all_reports()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/reports');

        $response->assertStatus(200);
    }

    public function test_escalate_report()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/reports/RPT-123/escalate');

        $response->assertStatus(200);
    }

    public function test_apply_shadow_ban()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/shadow-ban', [
                'user_id' => 2,
                'reason' => 'Multiple violations',
                'duration' => 7,
            ]);

        $response->assertStatus(200);
    }

    public function test_remove_shadow_ban()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/admin/shadow-ban/2');

        $response->assertStatus(200);
    }
}

class MentorshipApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // UC-35: Mentorship Program
    public function test_request_mentor()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mentorship/request', [
                'skill_interest' => 'upcycling',
                'experience_level' => 'beginner',
                'goals' => ['learn_denim', 'learn_embroidery'],
            ]);

        $response->assertStatus(201);
    }

    public function test_apply_as_mentor()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mentorship/apply', [
                'expertise' => ['upcycling', 'refashion'],
                'years_experience' => 5,
                'specialties' => ['denim', 'embroidery'],
            ]);

        $response->assertStatus(200);
    }

    public function test_get_mentor_recommendations()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/mentorship/mentors?skill=upcycling');

        $response->assertStatus(200)
            ->assertJsonStructure(['mentors', 'total_available']);
    }

    public function test_request_match()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mentorship/match', [
                'mentor_id' => 2,
            ]);

        $response->assertStatus(200);
    }

    public function test_respond_to_match()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mentorship/match/MATCH-123/respond', [
                'action' => 'accept',
            ]);

        $response->assertStatus(200);
    }

    public function test_get_active_mentorships()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/mentorship/active');

        $response->assertStatus(200);
    }

    public function test_schedule_session()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mentorship/session', [
                'mentorship_id' => 'MTR-001',
                'date' => now()->addDays(7)->toDateString(),
                'topic' => 'Denim transformation basics',
            ]);

        $response->assertStatus(200);
    }
}