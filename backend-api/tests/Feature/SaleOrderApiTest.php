<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\SaleOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleOrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
    }

    public function test_seller_can_create_sale_order()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::create([
            'seller_id' => $seller->id,
            'category_id' => 1,
            'title' => 'Test Item',
            'price' => 100.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/sale-orders', [
                'transaction_id' => $transaction->id,
                'item_id' => $item->id,
                'total_amount' => 100.00,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('sale_orders', [
            'total_amount' => 100.00,
        ]);
    }

    public function test_can_get_sale_order()
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

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $saleOrder = SaleOrder::create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'total_amount' => 50.00,
            'platform_fee' => 5.00,
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson("/api/sale-orders/{$saleOrder->id}");

        $response->assertStatus(200)
            ->assertJson(['total_amount' => 50.00]);
    }

    public function test_can_calculate_dynamic_fee()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::create([
            'seller_id' => $seller->id,
            'category_id' => 1,
            'title' => 'Test Item',
            'price' => 100.00,
            'condition' => 'new',
            'status' => 'available',
        ]);

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $saleOrder = SaleOrder::create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'total_amount' => 100.00,
        ]);

        $response = $this->getJson("/api/sale-orders/{$saleOrder->id}/fee");

        $response->assertStatus(200)
            ->assertJson(['dynamic_fee' => 10.00]);
    }

    public function test_seller_can_update_tracking_number()
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

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'pending',
        ]);

        $saleOrder = SaleOrder::create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'total_amount' => 50.00,
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/sale-orders/{$saleOrder->id}/tracking", [
                'tracking_number' => 'TRACK123456',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sale_orders', [
            'id' => $saleOrder->id,
            'tracking_number' => 'TRACK123456',
        ]);
    }
}