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
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Latest gadgets and electronics'],
            ['name' => 'Grocery', 'slug' => 'grocery', 'description' => 'Fresh groceries and food items'],
            ['name' => 'Makeup', 'slug' => 'makeup', 'description' => 'Beauty and makeup products'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Trendy clothing and accessories'],
            ['name' => 'Home', 'slug' => 'home', 'description' => 'Home and kitchen essentials'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create products for each category
        $products_data = [
            'electronics' => [
                ['name' => 'Wireless Bluetooth Headphones', 'price' => 79.99, 'discount' => 59.99],
                ['name' => 'USB-C Fast Charging Cable', 'price' => 29.99, 'discount' => 19.99],
                ['name' => 'Smart Watch Pro', 'price' => 299.99, 'discount' => 249.99],
                ['name' => '4K Webcam', 'price' => 129.99, 'discount' => null],
                ['name' => 'Portable SSD 1TB', 'price' => 149.99, 'discount' => 119.99],
            ],
            'grocery' => [
                ['name' => 'Organic Pasta Pack', 'price' => 12.99, 'discount' => 9.99],
                ['name' => 'Extra Virgin Olive Oil', 'price' => 18.99, 'discount' => null],
                ['name' => 'Almond Butter 500g', 'price' => 14.99, 'discount' => 11.99],
                ['name' => 'Whole Grain Bread', 'price' => 5.99, 'discount' => 3.99],
                ['name' => 'Fresh Coffee Beans', 'price' => 16.99, 'discount' => 12.99],
            ],
            'makeup' => [
                ['name' => 'Matte Lipstick Set', 'price' => 24.99, 'discount' => 17.99],
                ['name' => 'Liquid Foundation', 'price' => 32.99, 'discount' => null],
                ['name' => 'Eye Shadow Palette', 'price' => 42.99, 'discount' => 29.99],
                ['name' => 'Mascara Pro', 'price' => 21.99, 'discount' => 15.99],
                ['name' => 'Face Moisturizer', 'price' => 28.99, 'discount' => 19.99],
            ],
            'fashion' => [
                ['name' => 'Cotton T-Shirt', 'price' => 34.99, 'discount' => 24.99],
                ['name' => 'Denim Jeans', 'price' => 89.99, 'discount' => 64.99],
                ['name' => 'Casual Sneakers', 'price' => 79.99, 'discount' => null],
                ['name' => 'Summer Dress', 'price' => 69.99, 'discount' => 49.99],
                ['name' => 'Leather Jacket', 'price' => 199.99, 'discount' => 159.99],
            ],
            'home' => [
                ['name' => 'Stainless Steel Cookware Set', 'price' => 129.99, 'discount' => 99.99],
                ['name' => 'LED Desk Lamp', 'price' => 39.99, 'discount' => 29.99],
                ['name' => 'Bath Towel Set', 'price' => 49.99, 'discount' => null],
                ['name' => 'Pillow Set (2 pieces)', 'price' => 59.99, 'discount' => 39.99],
                ['name' => 'Dinner Plates Set', 'price' => 44.99, 'discount' => 34.99],
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
                    'image' => null,
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
