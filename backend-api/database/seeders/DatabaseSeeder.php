<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\MaterialCategory;
use App\Models\Item;
use App\Models\Address;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories (only if not exists)
        if (Category::count() == 0) {
            $categories = [
                ['name' => 'Tops', 'slug' => 'tops'],
                ['name' => 'Bottoms', 'slug' => 'bottoms'],
                ['name' => 'Dresses', 'slug' => 'dresses'],
                ['name' => 'Outerwear', 'slug' => 'outerwear'],
                ['name' => 'Footwear', 'slug' => 'footwear'],
                ['name' => 'Accessories', 'slug' => 'accessories'],
            ];
            foreach ($categories as $cat) {
                Category::create($cat);
            }
        }

        // Material Categories (only if not exists)
        if (MaterialCategory::count() == 0) {
            $materials = [
                ['fabric_name' => 'Cotton', 'is_organic' => true, 'recycle_tier' => 1],
                ['fabric_name' => 'Polyester', 'is_organic' => false, 'recycle_tier' => 2],
                ['fabric_name' => 'Wool', 'is_organic' => true, 'recycle_tier' => 1],
                ['fabric_name' => 'Linen', 'is_organic' => true, 'recycle_tier' => 1],
                ['fabric_name' => 'Denim', 'is_organic' => false, 'recycle_tier' => 3],
                ['fabric_name' => 'Silk', 'is_organic' => true, 'recycle_tier' => 1],
            ];
            foreach ($materials as $mat) {
                MaterialCategory::create($mat);
            }
        }

        // Admin User
        if (User::where('email', 'admin@rewearit.com')->count() == 0) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@rewearit.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'trust_score' => 100.0,
                'eco_credits' => 1000,
                'preferred_currency' => 'USD',
            ]);
            Admin::create(['user_id' => $admin->id, 'admin_role' => 'super_admin']);
        }

        // Seller User 1
        if (User::where('email', 'seller@rewearit.com')->count() == 0) {
            $seller1 = User::create([
                'name' => 'Eco Seller',
                'email' => 'seller@rewearit.com',
                'password' => Hash::make('password123'),
                'role' => 'seller',
                'trust_score' => 95.5,
                'eco_credits' => 500,
                'preferred_currency' => 'USD',
            ]);
            $seller1Profile = Seller::create([
                'user_id' => $seller1->id,
                'seller_verification_status' => 'verified',
                'payout_wallet_address' => '0x1234567890abcdef',
                'total_sales' => 45,
            ]);
        } else {
            $seller1 = User::where('email', 'seller@rewearit.com')->first();
        }

        // Seller User 2
        if (User::where('email', 'vintage@rewearit.com')->count() == 0) {
            $seller2 = User::create([
                'name' => 'Vintage Verk',
                'email' => 'vintage@rewearit.com',
                'password' => Hash::make('password123'),
                'role' => 'seller',
                'trust_score' => 88.0,
                'eco_credits' => 320,
                'preferred_currency' => 'EUR',
            ]);
            Seller::create([
                'user_id' => $seller2->id,
                'seller_verification_status' => 'verified',
                'payout_wallet_address' => '0xabcdef1234567890',
                'total_sales' => 23,
            ]);
        } else {
            $seller2 = User::where('email', 'vintage@rewearit.com')->first();
        }

        // Buyer User
        if (User::where('email', 'buyer@rewearit.com')->count() == 0) {
            $buyer = User::create([
                'name' => 'Green Buyer',
                'email' => 'buyer@rewearit.com',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
                'trust_score' => 92.0,
                'eco_credits' => 150,
                'preferred_currency' => 'USD',
            ]);
            Buyer::create([
                'user_id' => $buyer->id,
                'payment_info' => json_encode(['card_last4' => '4242']),
            ]);

            // Buyer addresses
            Address::create([
                'user_id' => $buyer->id,
                'type' => 'shipping',
                'full_name' => 'Green Buyer',
                'phone' => '+1234567890',
                'address_line1' => '123 Eco Street',
                'city' => 'Portland',
                'state' => 'Oregon',
                'postal_code' => '97201',
                'country' => 'USA',
                'is_default' => true,
            ]);
        } else {
            $buyer = User::where('email', 'buyer@rewearit.com')->first();
        }

        // Sample Items (only if none exist)
        if (Item::count() == 0) {
            $items = [
                [
                    'seller_id' => $seller1->id,
                    'category_id' => 1,
                    'material_category_id' => 1,
                    'title' => 'Organic Cotton Vintage Tee',
                    'description' => 'Beautiful vintage organic cotton t-shirt from the 90s. Excellent condition.',
                    'price' => 25.00,
                    'condition' => 'like_new',
                    'status' => 'available',
                    'carbon_savings' => 2.5,
                ],
                [
                    'seller_id' => $seller1->id,
                    'category_id' => 2,
                    'material_category_id' => 5,
                    'title' => 'Upcycled Denim Jeans',
                    'description' => 'Handmade upcycled denim jeans. Unique design with patches.',
                    'price' => 65.00,
                    'condition' => 'good',
                    'status' => 'available',
                    'carbon_savings' => 5.2,
                ],
                [
                    'seller_id' => $seller1->id,
                    'category_id' => 3,
                    'material_category_id' => 4,
                    'title' => 'Vintage Linen Summer Dress',
                    'description' => 'Breathable linen dress perfect for summer. Natural dyes.',
                    'price' => 45.00,
                    'condition' => 'good',
                    'status' => 'available',
                    'carbon_savings' => 3.8,
                ],
                [
                    'seller_id' => $seller2->id,
                    'category_id' => 4,
                    'material_category_id' => 3,
                    'title' => 'Recycled Wool Cardigan',
                    'description' => 'Warm wool cardigan from recycled materials. Perfect for layering.',
                    'price' => 55.00,
                    'condition' => 'like_new',
                    'status' => 'available',
                    'carbon_savings' => 4.1,
                ],
                [
                    'seller_id' => $seller2->id,
                    'category_id' => 1,
                    'material_category_id' => 2,
                    'title' => 'Repurposed Polyester Blouse',
                    'description' => 'Modern blouse made from recycled polyester. Unique print.',
                    'price' => 30.00,
                    'condition' => 'new',
                    'status' => 'available',
                    'carbon_savings' => 1.8,
                ],
                [
                    'seller_id' => $seller2->id,
                    'category_id' => 6,
                    'material_category_id' => 6,
                    'title' => 'Silk Scarf - Hand Painted',
                    'description' => 'Beautiful hand-painted silk scarf. One of a kind.',
                    'price' => 40.00,
                    'condition' => 'new',
                    'status' => 'available',
                    'carbon_savings' => 1.2,
                ],
                [
                    'seller_id' => $seller1->id,
                    'category_id' => 5,
                    'material_category_id' => 5,
                    'title' => 'Vintage Leather Boots',
                    'description' => 'Genuine vintage leather boots. Well maintained.',
                    'price' => 85.00,
                    'condition' => 'good',
                    'status' => 'available',
                    'carbon_savings' => 6.5,
                ],
                [
                    'seller_id' => $seller1->id,
                    'category_id' => 2,
                    'material_category_id' => 1,
                    'title' => 'Organic Cotton Shorts',
                    'description' => 'Comfortable organic cotton shorts. Great for summer.',
                    'price' => 20.00,
                    'condition' => 'new',
                    'status' => 'available',
                    'carbon_savings' => 1.5,
                ],
            ];

            foreach ($items as $item) {
                Item::create($item);
            }
        }

        echo "Seed data created successfully!\n";
        echo "Test Accounts:\n";
        echo "- Admin: admin@rewearit.com / password123\n";
        echo "- Seller: seller@rewearit.com / password123\n";
        echo "- Vintage: vintage@rewearit.com / password123\n";
        echo "- Buyer: buyer@rewearit.com / password123\n";
    }
}