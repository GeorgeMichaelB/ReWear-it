<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category', 'images'])->where('status', 'available');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->size) {
            $query->where('size', $request->size);
        }

        if ($request->seller_id) {
            $query->where('seller_id', $request->seller_id);
        }

        $products = $query->latest()->paginate(20);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,like_new,good,fair',
            'size' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'seller_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'size' => $request->size,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'status' => 'available',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return response()->json($product->load('images', 'seller', 'category'), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('images', 'seller', 'category'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'condition' => 'sometimes|in:new,like_new,good,fair',
            'size' => 'sometimes|string|max:50',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'sometimes|exists:categories,id',
            'status' => 'sometimes|in:available,sold,reserved,hidden',
        ]);

        $product->update($request->only([
            'title', 'description', 'price', 'condition', 'size', 'brand', 'category_id', 'status'
        ]));

        return response()->json($product->load('images', 'seller', 'category'));
    }

    public function destroy(Request $request, Product $product)
    {
        if ($product->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function myProducts(Request $request)
    {
        $products = Product::with('images', 'category')
            ->where('seller_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($products);
    }
}