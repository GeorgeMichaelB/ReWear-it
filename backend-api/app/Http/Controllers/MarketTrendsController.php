<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketTrendsController extends Controller
{
    // UC-36: Market trend data - highly swappable/sellable materials
    public function getMaterialTrends(Request $request)
    {
        $period = $request->period ?? '30_days';

        $trends = [
            'most_swappable' => [
                ['material' => 'Denim', 'swap_rate' => '85%', 'avg_days_to_swap' => 5, 'demand_score' => 95],
                ['material' => 'Cotton', 'swap_rate' => '78%', 'avg_days_to_swap' => 7, 'demand_score' => 88],
                ['material' => 'Wool', 'swap_rate' => '72%', 'avg_days_to_swap' => 10, 'demand_score' => 82],
                ['material' => 'Silk', 'swap_rate' => '65%', 'avg_days_to_swap' => 12, 'demand_score' => 75],
                ['material' => 'Linen', 'swap_rate' => '60%', 'avg_days_to_swap' => 14, 'demand_score' => 70],
            ],
            'most_sellable' => [
                ['material' => 'Designer Denim', 'sell_rate' => '92%', 'avg_price' => 85, 'demand_score' => 98],
                ['material' => 'Vintage Leather', 'sell_rate' => '88%', 'avg_price' => 120, 'demand_score' => 95],
                ['material' => 'Organic Cotton', 'sell_rate' => '82%', 'avg_price' => 45, 'demand_score' => 90],
                ['material' => 'Recycled Synthetics', 'sell_rate' => '75%', 'avg_price' => 35, 'demand_score' => 85],
            ],
            'rising_trends' => [
                ['trend' => 'Upcycled Streetwear', 'growth' => '+45%', 'category' => 'tops'],
                ['trend' => 'Vintage Workwear', 'growth' => '+38%', 'category' => 'outerwear'],
                ['trend' => 'Hand-patched Items', 'growth' => '+32%', 'category' => 'accessories'],
                ['trend' => 'Natural Dye Pieces', 'growth' => '+28%', 'category' => 'all'],
            ],
            'declining' => [
                ['trend' => 'Fast Fashion Basics', 'decline' => '-15%'],
                ['trend' => 'Synthetic Activewear', 'decline' => '-12%'],
            ],
        ];

        return response()->json([
            'period' => $period,
            'trends' => $trends,
            'insights' => [
                'Denim remains the #1 most swapped material - consider listing your denim items!',
                'Upcycled streetwear is seeing 45% growth - premium pricing possible.',
                'Vintage leather items sell 88% of the time - high demand!',
            ],
        ]);
    }

    // UC-36: Category performance
    public function getCategoryPerformance()
    {
        return response()->json([
            'categories' => [
                ['name' => 'Tops', 'listings' => 1250, 'sold' => 980, 'sell_rate' => '78%', 'avg_price' => 35],
                ['name' => 'Bottoms', 'listings' => 890, 'sold' => 720, 'sell_rate' => '81%', 'avg_price' => 45],
                ['name' => 'Dresses', 'listings' => 650, 'sold' => 520, 'sell_rate' => '80%', 'avg_price' => 55],
                ['name' => 'Outerwear', 'listings' => 420, 'sold' => 380, 'sell_rate' => '90%', 'avg_price' => 75],
                ['name' => 'Accessories', 'listings' => 780, 'sold' => 590, 'sell_rate' => '76%', 'avg_price' => 25],
            ],
        ]);
    }

    // UC-36: Price recommendations
    public function getPriceRecommendations(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'condition' => 'required|string',
            'material' => 'sometimes|string',
        ]);

        $recommendations = [
            'recommended_price' => rand(30, 100),
            'price_range' => [
                'min' => rand(20, 40),
                'max' => rand(80, 150),
            ],
            'similar_items_sold' => rand(10, 50),
            'market_demand' => 'high',
            'pricing_tips' => [
                'Include clear photos from multiple angles',
                'Highlight any upcycling or unique features',
                'Accurately describe condition for faster sales',
            ],
        ];

        return response()->json($recommendations);
    }

    // UC-36: Seasonal trends
    public function getSeasonalTrends(Request $request)
    {
        $season = $request->season ?? 'spring';

        return response()->json([
            'season' => $season,
            'trending_now' => [
                'Light denim jackets',
                'Midi dresses',
                'Linen pants',
                'Canvas tote bags',
            ],
            'price_tips' => [
                'Spring: Light layers in demand',
                'Summer: Breezy fabrics popular',
                'Fall: Outerwear picks up',
                'Winter: Cozy knits trend',
            ],
            'projected_demand' => [
                'denim' => '+15%',
                'cotton' => '+10%',
                'wool' => '-5%',
                'synthetic' => '-20%',
            ],
        ]);
    }
}