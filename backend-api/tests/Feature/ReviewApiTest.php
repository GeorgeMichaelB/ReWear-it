<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
    }

    public function test_can_create_review()
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
            'status' => 'completed',
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/reviews', [
                'transaction_id' => $transaction->id,
                'rating' => 5,
                'eco_friendliness_score' => 4,
                'comment' => 'Great eco-friendly product!',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', [
            'rating' => 5,
            'eco_friendliness_score' => 4,
        ]);
    }

    public function test_cannot_review_same_transaction_twice()
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
            'status' => 'completed',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => $buyer->id,
            'reviewee_id' => $seller->id,
            'rating' => 4,
            'eco_friendliness_score' => 4,
        ]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/reviews', [
                'transaction_id' => $transaction->id,
                'rating' => 5,
                'eco_friendliness_score' => 5,
            ]);

        $response->assertStatus(400);
    }

    public function test_can_get_reviews_for_user()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => 'completed',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => $buyer->id,
            'reviewee_id' => $seller->id,
            'rating' => 5,
            'eco_friendliness_score' => 4,
        ]);

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson('/api/reviews');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}