<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
    }

    public function test_can_create_transaction()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::create([
            'seller_id' => $seller->id,
            'category_id' => 1,
            'title' => 'Test Item',
            'price' => 50.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/transactions', [
                'item_id' => $item->id,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);
    }

    public function test_cannot_buy_own_item()
    {
        $seller = User::factory()->create();

        $item = Item::create([
            'seller_id' => $seller->id,
            'category_id' => 1,
            'title' => 'My Item',
            'price' => 50.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/transactions', [
                'item_id' => $item->id,
            ]);

        $response->assertStatus(400);
    }

    public function test_can_get_own_transactions()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_buyer_can_cancel_transaction()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson("/api/transactions/{$transaction->id}/cancel");

        $response->assertStatus(200);
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_seller_can_complete_transaction()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson("/api/transactions/{$transaction->id}/complete");

        $response->assertStatus(200);
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed',
        ]);
    }
}