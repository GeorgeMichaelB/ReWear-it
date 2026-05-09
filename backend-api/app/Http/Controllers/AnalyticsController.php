<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    // UC-32: Seller visual performance analytics
    public function getSellerAnalytics(Request $request)
    {
        $sellerId = $request->seller_id ?? 1;

        $analytics = [
            'seller_id' => $sellerId,
            'period' => $request->period ?? '30_days',
            'sales' => [
                'total_revenue' => rand(500, 5000),
                'total_orders' => rand(10, 100),
                'average_order_value' => rand(30, 150),
                'conversion_rate' => rand(5, 20) . '%',
                'growth_vs_previous' => rand(-10, 30) . '%',
            ],
            'views' => [
                'total_views' => rand(1000, 10000),
                'unique_visitors' => rand(500, 3000),
                'avg_time_on_listings' => rand(30, 180) . ' seconds',
                'search_appearances' => rand(2000, 8000),
            ],
            'sustainability_impact' => [
                'co2_saved_kg' => rand(50, 500),
                'water_saved_liters' => rand(1000, 10000),
                'waste_diverted_kg' => rand(20, 200),
                'upcycled_items_sold' => rand(15, 80),
            ],
            'top_performing_items' => [
                ['name' => 'Vintage Denim Jacket', 'views' => 450, 'sales' => 12],
                ['name' => 'Hand-painted Tote Bag', 'views' => 380, 'sales' => 8],
                ['name' => 'Recycled Denim Skirt', 'views' => 290, 'sales' => 6],
            ],
        ];

        return response()->json($analytics);
    }

    // UC-32: Get visual charts data
    public function getChartData(Request $request)
    {
        $type = $request->type ?? 'sales';
        
        $data = match($type) {
            'sales' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'values' => [120, 190, 300, 250, 200, 350],
            ],
            'views' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'values' => [1000, 1500, 2000, 1800, 2200, 2800],
            ],
            'sustainability' => [
                'labels' => ['CO2 (kg)', 'Water (L)', 'Waste (kg)'],
                'values' => [250, 5000, 100],
            ],
            default => [],
        };

        return response()->json([
            'chart_type' => $type,
            'data' => $data,
        ]);
    }

    // UC-40: System health monitoring
    public function getSystemHealth(Request $request)
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toDateTimeString(),
            'metrics' => [
                'transaction_failure_rate' => rand(1, 5) . '%',
                'listing_latency_ms' => rand(50, 200),
                'api_response_time_ms' => rand(100, 300),
                'database_query_time_ms' => rand(10, 50),
                'active_users' => rand(100, 500),
                'active_listings' => rand(1000, 5000),
            ],
            'services' => [
                ['name' => 'API', 'status' => 'up', 'uptime' => '99.9%'],
                ['name' => 'Database', 'status' => 'up', 'uptime' => '99.8%'],
                ['name' => 'Cache', 'status' => 'up', 'uptime' => '99.5%'],
                ['name' => 'Queue', 'status' => 'up', 'uptime' => '100%'],
            ],
            'alerts' => [
                ['severity' => 'info', 'message' => 'All systems operational', 'timestamp' => now()->toDateTimeString()],
            ],
        ];

        return response()->json($health);
    }

    // UC-40: Get transaction failure details
    public function getTransactionFailures(Request $request)
    {
        return response()->json([
            'total_failures' => rand(5, 20),
            'failure_rate' => rand(1, 5) . '%',
            'failures' => [
                ['id' => 'tx-1', 'type' => 'payment', 'reason' => 'Insufficient funds', 'timestamp' => now()->subHours(1)->toDateTimeString()],
                ['id' => 'tx-2', 'type' => 'shipping', 'reason' => 'Address not found', 'timestamp' => now()->subHours(3)->toDateTimeString()],
            ],
            'resolution_suggestions' => [
                'Enable retry logic for payment failures',
                'Validate addresses before order confirmation',
            ],
        ]);
    }

    // UC-40: Get listing latency metrics
    public function getListingLatency()
    {
        return response()->json([
            'average_latency_ms' => rand(100, 300),
            'p95_latency_ms' => rand(500, 800),
            'p99_latency_ms' => rand(1000, 1500),
            'slow_listings' => [
                ['listing_id' => 'lst-1', 'latency_ms' => 1200, 'operation' => 'create'],
                ['listing_id' => 'lst-2', 'latency_ms' => 950, 'operation' => 'update'],
            ],
        ]);
    }
}