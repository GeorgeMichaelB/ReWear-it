<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Dispute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisputeApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
    }

    public function test_can_create_dispute()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'seller_id' => $seller->id,
            'category_id' => 1,
            'title' => 'Test Item',
            'price' => 50.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/disputes', [
                'transaction_id' => $transaction->id,
                'reason' => 'Item not as described',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('disputes', [
            'reason' => 'Item not as described',
            'resolution_status' => 'open',
        ]);
    }

    public function test_can_upload_evidence()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $dispute = Dispute::create([
            'transaction_id' => $transaction->id,
            'reporter_id' => $buyer->id,
            'reason' => 'Test dispute',
            'resolution_status' => 'open',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson("/api/disputes/{$dispute->id}/evidence", [
                'photo_url' => 'https://example.com/photo.jpg',
            ]);

        $response->assertStatus(200);
    }

    public function test_can_get_own_disputes()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        Dispute::create([
            'transaction_id' => $transaction->id,
            'reporter_id' => $buyer->id,
            'reason' => 'My dispute',
            'resolution_status' => 'open',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->getJson('/api/disputes');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}