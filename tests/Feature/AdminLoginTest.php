<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to admin login when accessing admin dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});

test('logged in admin accessing admin login page is redirected to dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.login'))
        ->assertRedirect(route('admin.dashboard'));
});

test('logged in customer accessing admin login page is redirected to home with error', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get(route('admin.login'))
        ->assertRedirect(route('home'))
        ->assertSessionHas('error', 'Customers cannot access the administrator portal.');
});

test('admin can log in via admin login endpoint', function () {
    $admin = User::factory()->create([
        'email' => 'admin@shopee.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@shopee.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);
});

test('customer cannot log in via admin login endpoint and is logged out', function () {
    User::factory()->create([
        'email' => 'customer@shopee.com',
        'password' => bcrypt('password123'),
        'role' => 'customer',
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'customer@shopee.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('guest login with invalid credentials fails on admin login endpoint', function () {
    $response = $this->post('/admin/login', [
        'email' => 'invalid@shopee.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});
