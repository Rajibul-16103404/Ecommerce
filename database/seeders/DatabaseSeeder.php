<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create vendor users
        $vendors = User::factory(3)->state(['role' => 'vendor'])->create();
        $vendors->each(function ($vendor) {
            $vendor->vendorProfile()->create([
                'shop_name' => $vendor->name.'\'s Shop',
                'description' => 'Quality products from '.$vendor->name,
                'is_verified' => true,
                'commission_rate' => 15.00,
            ]);
        });

        // Create customer users
        User::factory(10)->state(['role' => 'customer'])->create();

        // Create categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Latest gadgets and electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800'],
            ['name' => 'Grocery', 'slug' => 'grocery', 'description' => 'Fresh groceries and food items', 'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800'],
            ['name' => 'Makeup', 'slug' => 'makeup', 'description' => 'Beauty and makeup products', 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Trendy clothing and accessories', 'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800'],
            ['name' => 'Home', 'slug' => 'home', 'description' => 'Home and kitchen essentials', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create products for each category
        $products_data = [
            'electronics' => [
                ['name' => 'Wireless Bluetooth Headphones', 'price' => 79.99, 'discount' => 59.99, 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800'],
                ['name' => 'USB-C Fast Charging Cable', 'price' => 29.99, 'discount' => 19.99, 'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800'],
                ['name' => 'Smart Watch Pro', 'price' => 299.99, 'discount' => 249.99, 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800'],
                ['name' => '4K Webcam', 'price' => 129.99, 'discount' => null, 'image' => 'https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b?w=800'],
                ['name' => 'Portable SSD 1TB', 'price' => 149.99, 'discount' => 119.99, 'image' => 'https://images.unsplash.com/photo-1601524909162-be87252be298?w=800'],
            ],
            'grocery' => [
                ['name' => 'Organic Pasta Pack', 'price' => 12.99, 'discount' => 9.99, 'image' => 'https://images.unsplash.com/photo-1621961424579-f15568e61203?w=800'],
                ['name' => 'Extra Virgin Olive Oil', 'price' => 18.99, 'discount' => null, 'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800'],
                ['name' => 'Almond Butter 500g', 'price' => 14.99, 'discount' => 11.99, 'image' => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?w=800'],
                ['name' => 'Whole Grain Bread', 'price' => 5.99, 'discount' => 3.99, 'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800'],
                ['name' => 'Fresh Coffee Beans', 'price' => 16.99, 'discount' => 12.99, 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800'],
            ],
            'makeup' => [
                ['name' => 'Matte Lipstick Set', 'price' => 24.99, 'discount' => 17.99, 'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=800'],
                ['name' => 'Liquid Foundation', 'price' => 32.99, 'discount' => null, 'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800'],
                ['name' => 'Eye Shadow Palette', 'price' => 42.99, 'discount' => 29.99, 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800'],
                ['name' => 'Mascara Pro', 'price' => 21.99, 'discount' => 15.99, 'image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=800'],
                ['name' => 'Face Moisturizer', 'price' => 28.99, 'discount' => 19.99, 'image' => 'https://images.unsplash.com/photo-1608248597481-496100c80836?w=800'],
            ],
            'fashion' => [
                ['name' => 'Cotton T-Shirt', 'price' => 34.99, 'discount' => 24.99, 'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800'],
                ['name' => 'Denim Jeans', 'price' => 89.99, 'discount' => 64.99, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800'],
                ['name' => 'Casual Sneakers', 'price' => 79.99, 'discount' => null, 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800'],
                ['name' => 'Summer Dress', 'price' => 69.99, 'discount' => 49.99, 'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800'],
                ['name' => 'Leather Jacket', 'price' => 199.99, 'discount' => 159.99, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'],
            ],
            'home' => [
                ['name' => 'Stainless Steel Cookware Set', 'price' => 129.99, 'discount' => 99.99, 'image' => 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=800'],
                ['name' => 'LED Desk Lamp', 'price' => 39.99, 'discount' => 29.99, 'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800'],
                ['name' => 'Bath Towel Set', 'price' => 49.99, 'discount' => null, 'image' => 'https://images.unsplash.com/photo-1563453392212-326f5e854473?w=800'],
                ['name' => 'Pillow Set (2 pieces)', 'price' => 59.99, 'discount' => 39.99, 'image' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=800'],
                ['name' => 'Dinner Plates Set', 'price' => 44.99, 'discount' => 34.99, 'image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=800'],
            ],
        ];

        foreach ($products_data as $slug => $products) {
            $category = Category::where('slug', $slug)->first();
            $vendor = $vendors->random();

            foreach ($products as $prod) {
                Product::create([
                    'category_id' => $category->id,
                    'vendor_id' => $vendor->id,
                    'name' => $prod['name'],
                    'slug' => str()->slug($prod['name']),
                    'description' => 'High quality '.$prod['name'].' - perfect for your needs. Carefully selected for quality and value.',
                    'price' => $prod['price'],
                    'discount_price' => $prod['discount'],
                    'stock' => random_int(5, 50),
                    'image' => $prod['image'],
                    'views' => random_int(10, 500),
                ]);
            }
        }

        // Create reviews
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        foreach ($products->random(15) as $product) {
            Review::factory()
                ->count(random_int(2, 5))
                ->create([
                    'product_id' => $product->id,
                    'user_id' => $customers->random()->id,
                ]);
        }
    }
}
