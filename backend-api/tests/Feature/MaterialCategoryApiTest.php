<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MaterialCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_material_categories()
    {
        MaterialCategory::create(['fabric_name' => 'Cotton', 'is_organic' => true, 'recycle_tier' => 1]);
        MaterialCategory::create(['fabric_name' => 'Polyester', 'is_organic' => false, 'recycle_tier' => 2]);

        $response = $this->getJson('/api/material-categories');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_create_material_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/material-categories', [
                'fabric_name' => 'Wool',
                'is_organic' => true,
                'recycle_tier' => 1,
            ]);

        $response->assertStatus(201)
            ->assertJson(['fabric_name' => 'Wool']);
    }

    public function test_can_get_single_material_category()
    {
        $category = MaterialCategory::create(['fabric_name' => 'Linen', 'is_organic' => true, 'recycle_tier' => 1]);

        $response = $this->getJson("/api/material-categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['fabric_name' => 'Linen']);
    }

    public function test_can_delete_material_category()
    {
        $user = User::factory()->create();
        $category = MaterialCategory::create(['fabric_name' => 'Silk', 'is_organic' => true, 'recycle_tier' => 1]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/material-categories/{$category->id}");

        $response->assertStatus(200);
    }
}