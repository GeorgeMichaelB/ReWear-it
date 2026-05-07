<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $orders = Order::with('items.product', 'buyer')->latest()->paginate(20);
        } else {
            $orders = Order::with('items.product')
                ->where('buyer_id', $request->user()->id)
                ->latest()
                ->paginate(20);
        }

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->status !== 'available') {
                return response()->json([
                    'message' => "Product {$product->title} is no longer available"
                ], 400);
            }

            $totalAmount += $product->price * $item['quantity'];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        $order = Order::create([
            'buyer_id' => $request->user()->id,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            Product::where('id', $item['product_id'])->update(['status' => 'reserved']);
        }

        return response()->json($order->load('items.product', 'buyer'), 201);
    }

    public function show(Order $order, Request $request)
    {
        if ($order->buyer_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load('items.product', 'buyer', 'buyer.addresses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled,refunded',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'cancelled' || $request->status === 'refunded') {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->update(['status' => 'available']);
            }
        }

        return response()->json($order->load('items.product', 'buyer'));
    }

    public function sellerOrders(Request $request)
    {
        $productIds = $request->user()->products()->pluck('id');

        $orders = Order::whereHas('items', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })->with('items.product', 'buyer')->latest()->paginate(20);

        return response()->json($orders);
    }
}