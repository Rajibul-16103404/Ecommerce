<?php

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can access cart index page, but is redirected to login for wishlist and to cart index for checkout when empty', function () {
    $this->get(route('cart.index'))->assertSuccessful();
    $this->get(route('wishlist.index'))->assertRedirect(route('login'));
    $this->get(route('checkout'))->assertRedirect(route('cart.index'));
});

test('user can register, login and logout', function () {
    // Register
    $registerResponse = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $registerResponse->assertRedirect(route('home'));
    $this->assertDatabaseHas('users', ['email' => 'john@example.com', 'role' => 'customer']);

    // Logout
    $logoutResponse = $this->post(route('logout'));
    $logoutResponse->assertRedirect('/');
    expect(auth()->check())->toBeFalse();

    // Login
    $loginResponse = $this->post('/login', [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);
    $loginResponse->assertRedirect(route('home'));
    expect(auth()->check())->toBeTrue();
});

test('customer can add items, update quantities, and remove from cart', function () {
    $user = User::factory()->state(['role' => 'customer'])->create();
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $user->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    $this->actingAs($user);

    // Add to cart
    $addResponse = $this->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
    $addResponse->assertSessionHasNoErrors();
    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    // Update quantity
    $updateResponse = $this->put(route('cart.update', $product->id), [
        'quantity' => 5,
    ]);
    $updateResponse->assertSessionHasNoErrors();
    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 5,
    ]);

    // Remove from cart
    $removeResponse = $this->delete(route('cart.remove', $product->id));
    $removeResponse->assertSessionHasNoErrors();
    $this->assertDatabaseMissing('cart_items', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('customer can add and remove items from wishlist', function () {
    $user = User::factory()->state(['role' => 'customer'])->create();
    $category = Category::create(['name' => 'Home', 'slug' => 'home']);
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $user->id,
        'name' => 'Comfy Sofa',
        'slug' => 'comfy-sofa',
        'description' => 'A very soft sofa.',
        'price' => 450.00,
        'stock' => 3,
    ]);

    $this->actingAs($user);

    // Add to wishlist
    $this->post(route('wishlist.add'), ['product_id' => $product->id])
        ->assertSessionHasNoErrors();
    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    // Remove from wishlist
    $this->delete(route('wishlist.remove', $product->id))
        ->assertSessionHasNoErrors();
    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('customer checkout decrements stock, records manual payment details, and clears cart', function () {
    $user = User::factory()->state(['role' => 'customer'])->create();
    $category = Category::create(['name' => 'Grocery', 'slug' => 'grocery']);
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $user->id,
        'name' => 'Hazelnut Spread',
        'slug' => 'hazelnut-spread',
        'description' => 'Choco spread.',
        'price' => 8.00,
        'stock' => 15,
    ]);

    $this->actingAs($user);

    // Add to cart
    CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    // Checkout
    $checkoutResponse = $this->post(route('orders.store'), [
        'shipping_address' => '123 Tech Lane',
        'shipping_city' => 'Dhaka',
        'shipping_zip' => '94025',
        'shipping_phone' => '01711111111',
        'payment_method' => 'bkash',
        'payment_sender' => '01711111111',
        'transaction_id' => 'BKX998877',
    ]);

    $checkoutResponse->assertSessionHasNoErrors();

    // Verify order created
    $order = Order::where('user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->payment_method)->toBe('bkash');
    expect($order->transaction_id)->toBe('BKX998877');
    expect($order->payment_status)->toBe('pending_verification');
    expect($order->total_price)->toEqual(84.00); // (8.00 * 3) + 60.00 (Dhaka) = 84.00

    // Verify stock is decremented (15 - 3 = 12)
    $product->refresh();
    expect($product->stock)->toBe(12);

    // Verify cart is cleared
    $cartCount = CartItem::where('user_id', $user->id)->count();
    expect($cartCount)->toBe(0);
});

test('only admin users can access the admin dashboard and change status', function () {
    $customer = User::factory()->state(['role' => 'customer'])->create();
    $admin = User::factory()->state(['role' => 'admin'])->create();

    // Customer redirect check
    $this->actingAs($customer)
        ->get(route('admin.dashboard'))
        ->assertRedirect('/')
        ->assertSessionHas('error');

    // Admin access check
    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertStatus(200);

    // Create an order to status update
    $order = Order::create([
        'user_id' => $customer->id,
        'order_number' => 'ORD-TEST1234',
        'total_price' => 50.00,
        'status' => 'pending',
        'payment_status' => 'pending_verification',
        'shipping_address' => 'Test address',
        'shipping_city' => 'Test City',
        'shipping_zip' => '1234',
        'shipping_phone' => '01700000000',
        'payment_method' => 'bkash',
        'payment_sender' => '01700000000',
        'transaction_id' => 'TXN1234',
    ]);

    // Admin verifies payment and approves order
    $this->actingAs($admin)
        ->put(route('admin.orders.update-payment', $order->id), [
            'payment_status' => 'paid',
        ])->assertSessionHasNoErrors();

    $this->actingAs($admin)
        ->put(route('admin.orders.update-status', $order->id), [
            'status' => 'processing',
        ])->assertSessionHasNoErrors();

    $order->refresh();
    expect($order->payment_status)->toBe('paid');
    expect($order->status)->toBe('processing');
});

test('guest with items in session cart can access checkout page', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    $this->withSession(['cart' => [$product->id => 2]])
        ->get(route('checkout'))
        ->assertSuccessful()
        ->assertSee($product->name)
        ->assertSee('৳40.00'); // 20.00 * 2 = 40.00
});

test('guest can add, update, and remove items from session cart', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    // Add to session cart
    $this->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertSessionHasNoErrors()->assertRedirect();

    expect(session('cart'))->toBe([$product->id => 2]);

    // Update quantity in session cart
    $this->put(route('cart.update', $product->id), [
        'quantity' => 5,
    ])->assertSessionHasNoErrors()->assertRedirect();

    expect(session('cart'))->toBe([$product->id => 5]);

    // Remove from session cart
    $this->delete(route('cart.remove', $product->id))
        ->assertSessionHasNoErrors()->assertRedirect();

    expect(session('cart'))->toBe([]);
});

test('guest checkout with create_account checked registers user and processes order', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    // Add item to guest cart session
    $this->withSession(['cart' => [$product->id => 2]]);

    // Checkout request
    $checkoutResponse = $this->post(route('orders.store'), [
        'name' => 'Jane Guest',
        'email' => 'jane@example.com',
        'create_account' => '1',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'shipping_address' => '456 Guest Lane',
        'shipping_city' => 'Dhaka',
        'shipping_zip' => '1209',
        'shipping_phone' => '01811111111',
        'payment_method' => 'cod',
    ]);

    $checkoutResponse->assertSessionHasNoErrors();

    // Check user is registered and logged in
    $this->assertDatabaseHas('users', [
        'name' => 'Jane Guest',
        'email' => 'jane@example.com',
        'role' => 'customer',
    ]);
    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->email)->toBe('jane@example.com');

    // Check order created
    $order = Order::where('user_id', auth()->id())->first();
    expect($order)->not->toBeNull();
    expect($order->shipping_city)->toBe('Dhaka');
    expect($order->total_price)->toEqual(100.00); // (20 * 2) + 60.00 shipping = 100.00
});

test('guest checkout without create_account checked silently registers user and processes order', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    // Add item to guest cart session
    $this->withSession(['cart' => [$product->id => 2]]);

    // Checkout request
    $checkoutResponse = $this->post(route('orders.store'), [
        'name' => 'Silent Guest',
        'email' => 'silent@example.com',
        'create_account' => '0',
        'shipping_address' => '789 Silent Lane',
        'shipping_city' => 'Dhaka',
        'shipping_zip' => '1209',
        'shipping_phone' => '01911111111',
        'payment_method' => 'cod',
    ]);

    $checkoutResponse->assertSessionHasNoErrors();

    // Check user is silently created in users table
    $this->assertDatabaseHas('users', [
        'name' => 'Silent Guest',
        'email' => 'silent@example.com',
        'role' => 'customer',
    ]);
    // Guest should NOT be logged in
    expect(auth()->check())->toBeFalse();

    // Check order created
    $user = User::where('email', 'silent@example.com')->first();
    $order = Order::where('user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->shipping_city)->toBe('Dhaka');
    expect($order->total_price)->toEqual(100.00); // (20 * 2) + 60.00 shipping = 100.00
});

test('buy now button adds product and redirects to checkout', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    // Add to cart with buy_now action
    $response = $this->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'buy_now' => '1',
    ]);

    $response->assertRedirect(route('checkout'));
    expect(session('cart'))->toBe([$product->id => 1]);
});

test('location based shipping fee is applied at checkout', function () {
    $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
    $vendor = User::factory()->state(['role' => 'vendor'])->create();
    $product = Product::create([
        'category_id' => $category->id,
        'vendor_id' => $vendor->id,
        'name' => 'Stylish Cap',
        'slug' => 'stylish-cap',
        'description' => 'A nice cap.',
        'price' => 20.00,
        'stock' => 10,
    ]);

    // Guest with session cart items
    $this->withSession(['cart' => [$product->id => 1]]);

    // Select Outside Dhaka (120.05 shipping fee)
    $response = $this->post(route('orders.store'), [
        'name' => 'Jane Guest',
        'email' => 'jane2@example.com',
        'shipping_address' => 'Outside Dhaka Lane',
        'shipping_city' => 'Outside Dhaka',
        'shipping_zip' => '2000',
        'shipping_phone' => '01811111111',
        'payment_method' => 'cod',
    ]);

    $response->assertSessionHasNoErrors();

    $user = User::where('email', 'jane2@example.com')->first();
    $order = Order::where('user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->total_price)->toEqual(140.00); // 20.00 product + 120.00 Outside Dhaka shipping = 140.00
});
