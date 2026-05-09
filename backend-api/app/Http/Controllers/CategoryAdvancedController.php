<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryAdvancedController extends Controller
{
    // Category Functions (15-17)

    // 15. Get category tree (hierarchical)
    public function getCategoryTree()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        
        return response()->json(['categories' => $categories]);
    }

    // 16. Get category statistics
    public function getCategoryStats()
    {
        $categories = Category::withCount('items')->get();
        
        return response()->json([
            'categories' => $categories->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'item_count' => $cat->items_count ?? 0,
                    'popularity_rank' => 0,
                ];
            }),
            'total_items' => $categories->sum('items_count'),
            'total_categories' => $categories->count(),
        ]);
    }

    // 17. Get popular categories
    public function getPopularCategories()
    {
        $categories = Category::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(6)
            ->get();
        
        return response()->json(['popular_categories' => $categories]);
    }

    // 18. Get category by slug
    public function getBySlug($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return response()->json(['category' => $category]);
    }

    // 19. Get category breadcrumb
    public function getBreadcrumb($id)
    {
        $category = Category::findOrFail($id);
        $breadcrumb = [];
        
        $current = $category;
        while ($current) {
            array_unshift($breadcrumb, ['id' => $current->id, 'name' => $current->name]);
            $current = $current->parent;
        }
        
        return response()->json(['breadcrumb' => $breadcrumb]);
    }

    // 20. Get subcategories
    public function getSubcategories($id)
    {
        $category = Category::findOrFail($id);
        $subcategories = $category->children;
        
        return response()->json(['subcategories' => $subcategories]);
    }
}