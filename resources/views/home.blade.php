@extends('layouts.app')

@section('title', 'Home - ShopHub')

@section('content')
    <!-- Hero Section with Animation -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-32">
            <div class="text-center animate-fade-in">
                <h1 class="text-5xl sm:text-7xl font-bold mb-6 leading-tight">
                    <span class="block">Discover Your</span>
                    <span class="block text-transparent bg-gradient-to-r from-yellow-200 to-pink-200 bg-clip-text">Perfect Products</span>
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Shop from electronics, groceries, makeup, fashion, and everything in between. All in one place.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="px-8 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition transform hover:scale-105">
                        Shop Now
                    </a>
                    <a href="{{ route('categories.index') }}" class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-blue-600 transition">
                        Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Showcase -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">
            Shop by Category
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $categories = [
                    ['icon' => 'fa-laptop', 'name' => 'Electronics', 'color' => 'from-blue-500 to-blue-600'],
                    ['icon' => 'fa-apple-alt', 'name' => 'Grocery', 'color' => 'from-green-500 to-green-600'],
                    ['icon' => 'fa-lipstick', 'name' => 'Makeup', 'color' => 'from-pink-500 to-rose-600'],
                    ['icon' => 'fa-tshirt', 'name' => 'Fashion', 'color' => 'from-purple-500 to-purple-600'],
                    ['icon' => 'fa-home', 'name' => 'Home', 'color' => 'from-orange-500 to-orange-600'],
                ];
            @endphp
            @foreach ($categories as $cat)
                <a href="#" class="group">
                    <div class="bg-gradient-to-br {{ $cat['color'] }} rounded-lg p-8 text-white text-center transform transition group-hover:scale-110 duration-300 shadow-lg">
                        <i class="fas {{ $cat['icon'] }} text-4xl mb-4 block group-hover:animate-bounce"></i>
                        <p class="font-bold text-lg">{{ $cat['name'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section class="max-w-7xl mx-auto px-4 py-16 bg-gray-50 -mx-4 px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 px-4">
            Featured Products
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
            @for ($i = 1; $i <= 8; $i++)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105">
                    <!-- Product Image -->
                    <div class="relative bg-gradient-to-br from-blue-100 to-purple-100 h-48 flex items-center justify-center overflow-hidden group">
                        <i class="fas fa-image text-gray-400 text-5xl"></i>
                        <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold opacity-0 group-hover:opacity-100 transition">
                            -20%
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">Premium Product {{ $i }}</h3>
                        <p class="text-gray-500 text-sm mb-3">High quality product</p>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            @for ($j = 0; $j < 5; $j++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            <span class="text-gray-600 text-sm ml-2">({{ rand(50, 500) }} reviews)</span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-2xl font-bold text-blue-600">${{ rand(20, 100) }}</span>
                            <span class="text-sm text-gray-400 line-through">${{ rand(100, 150) }}</span>
                        </div>

                        <!-- Add to Cart Button -->
                        <button class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 group">
                            <i class="fas fa-shopping-cart group-hover:animate-bounce"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <!-- Promo Section -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="bg-gradient-to-r from-orange-500 to-pink-500 rounded-lg p-12 text-white text-center">
            <h2 class="text-4xl font-bold mb-4">Special Summer Sale!</h2>
            <p class="text-xl mb-8 opacity-90">Get up to 50% off on selected items</p>
            <button class="px-8 py-3 bg-white text-orange-600 font-bold rounded-lg hover:bg-gray-100 transition transform hover:scale-105">
                Discover Deals
            </button>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">Why Choose ShopHub?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-blue-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Fast Delivery</h3>
                <p class="text-gray-600">Get your products delivered in 2-3 days</p>
            </div>
            <div class="text-center">
                <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-green-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Secure Payment</h3>
                <p class="text-gray-600">Your transactions are 100% secure</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-purple-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Easy Returns</h3>
                <p class="text-gray-600">30-day money-back guarantee</p>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-gray-900 text-white py-16">
        <div class="max-w-2xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-gray-400 mb-8">Get exclusive deals and updates delivered to your inbox</p>
            <form class="flex gap-2">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg text-gray-900" required>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-bold transition">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
    </style>
@endsection
