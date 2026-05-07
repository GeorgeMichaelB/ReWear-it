<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_categories()
    {
        Category::create(['name' => 'Tops', 'slug' => 'tops']);
        Category::create(['name' => 'Bottoms', 'slug' => 'bottoms']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_get_single_category()
    {
        $category = Category::create(['name' => 'Dresses', 'slug' => 'dresses']);

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['name' => 'Dresses']);
    }

    public function test_can_create_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'New Category',
            ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'New Category']);
    }

    public function test_can_update_category()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Old Name', 'slug' => 'old-name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Name']);
    }

    public function test_can_delete_category()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Delete Me', 'slug' => 'delete-me']);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}