<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    // UC-41: Auto-curate weekly newsletter with top 5 trending upcycled items
    public function generateWeeklyNewsletter()
    {
        $trendingItems = [
            [
                'id' => 1,
                'name' => 'Upcycled Denim Jacket with Embroidery',
                'seller' => 'UpcycleQueen',
                'price' => 85,
                'views' => 1250,
                'swaps' => 45,
                'sustainability_score' => 95,
                'description' => 'Hand-embroidered vintage denim jacket',
            ],
            [
                'id' => 2,
                'name' => 'Patchwork Tote Bag from Recycled Fabrics',
                'seller' => 'EcoStitcher',
                'price' => 35,
                'views' => 980,
                'swaps' => 38,
                'sustainability_score' => 92,
                'description' => 'Unique patchwork design from fabric scraps',
            ],
            [
                'id' => 3,
                'name' => 'Natural Dye Midi Dress',
                'seller' => 'SustainableStyle',
                'price' => 55,
                'views' => 870,
                'swaps' => 32,
                'sustainability_score' => 98,
                'description' => 'Indigo Shibori dress with natural dyes',
            ],
            [
                'id' => 4,
                'name' => 'Refashioned Leather Crossbody',
                'seller' => 'VintageRevive',
                'price' => 65,
                'views' => 760,
                'swaps' => 28,
                'sustainability_score' => 88,
                'description' => 'Converted vintage leather bag',
            ],
            [
                'id' => 5,
                'name' => 'Visible Mending Sweater',
                'seller' => 'MendItForward',
                'price' => 40,
                'views' => 650,
                'swaps' => 25,
                'sustainability_score' => 90,
                'description' => 'Beautiful Sashiko-inspired repairs',
            ],
        ];

        $newsletter = [
            'id' => 'NL-' . date('Y-W'),
            'type' => 'weekly_trending',
            'generated_at' => now()->toDateTimeString(),
            'subject' => '🔥 This Week\'s Top 5 Upcycled Finds!',
            'preheader' => 'Discover the most trending sustainable pieces this week',
            'featured_items' => $trendingItems,
            'highlights' => [
                'Highest swap rate: Patchwork Tote (38 swaps)',
                'Most views: Denim Jacket (1,250 views)',
                'Top sustainability score: Natural Dye Dress (98)',
            ],
            'community_stats' => [
                'total_swaps_this_week' => rand(200, 500),
                'new_upcyclers' => rand(20, 50),
                'co2_saved_kg' => rand(100, 300),
            ],
        ];

        return response()->json([
            'message' => 'Weekly newsletter generated',
            'newsletter' => $newsletter,
            'preview' => [
                'subject' => $newsletter['subject'],
                'item_count' => count($trendingItems),
                'total_reach' => rand(5000, 15000),
            ],
        ]);
    }

    // UC-41: Send newsletter to subscribers
    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'newsletter_id' => 'required',
            'test_only' => 'sometimes|boolean',
        ]);

        $subscriberCount = rand(1000, 5000);
        
        return response()->json([
            'newsletter_id' => $request->newsletter_id,
            'status' => 'scheduled',
            'scheduled_for' => now()->addHours(2)->toDateTimeString(),
            'subscriber_count' => $request->test_only ? 5 : $subscriberCount,
            'estimated_open_rate' => rand(25, 45) . '%',
            'test_mode' => $request->test_only ?? false,
        ]);
    }

    // UC-41: Get newsletter subscribers
    public function getSubscribers()
    {
        return response()->json([
            'subscribers' => [
                ['email' => 'user1@example.com', 'preferences' => ['trending', 'new_drops']],
                ['email' => 'user2@example.com', 'preferences' => ['all', 'weekly']],
            ],
            'total' => rand(1000, 5000),
            'by_preference' => [
                'trending_only' => rand(300, 800),
                'all_updates' => rand(500, 2000),
                'new_drops' => rand(200, 500),
            ],
        ]);
    }

    // UC-41: Subscribe to newsletter
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'preferences' => 'sometimes|array',
        ]);

        return response()->json([
            'email' => $request->email,
            'subscribed' => true,
            'preferences' => $request->preferences ?? ['all'],
            'message' => 'Successfully subscribed to weekly newsletter!',
        ]);
    }

    // UC-41: Get past newsletters
    public function getPastNewsletters()
    {
        return response()->json([
            'newsletters' => [
                ['id' => 'NL-2025-18', 'subject' => 'Top 5 Vintage Finds', 'sent_at' => now()->subWeeks(1)->toDateTimeString(), 'open_rate' => '38%'],
                ['id' => 'NL-2025-17', 'subject' => 'Summer Upcycle Trends', 'sent_at' => now()->subWeeks(2)->toDateTimeString(), 'open_rate' => '42%'],
                ['id' => 'NL-2025-16', 'subject' => 'Eco-Friendly Fashion', 'sent_at' => now()->subWeeks(3)->toDateTimeString(), 'open_rate' => '35%'],
            ],
        ]);
    }
}