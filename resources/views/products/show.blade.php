@extends('layouts.app')

@section('title', $product->name . ' - ShopHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex text-sm">
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a>
                <span class="text-gray-500 mx-2">/</span>
                <a href="{{ route('categories.show', $product->category->slug) }}" class="text-blue-600 hover:underline">
                    {{ $product->category->name }}
                </a>
                <span class="text-gray-500 mx-2">/</span>
                <span class="text-gray-700">{{ $product->name }}</span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div x-data="{ activeImage: 0 }" class="space-y-4">
                <!-- Main Image -->
                <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg h-96 flex items-center justify-center overflow-hidden">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>

                <!-- Thumbnail Images -->
                <div class="flex gap-4">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="w-20 h-20 bg-gray-100 rounded-lg cursor-pointer hover:ring-2 hover:ring-blue-500 transition">
                            <i class="fas fa-image text-gray-400 text-3xl w-full h-full flex items-center justify-center"></i>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-8">
                <!-- Category & Stock -->
                <div>
                    <span class="text-sm text-blue-600 font-semibold">{{ $product->category->name }}</span>
                    <h1 class="text-4xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>
                </div>

                <!-- Rating & Reviews -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                        @endfor
                    </div>
                    <span class="text-gray-700 font-semibold">4.8 / 5.0</span>
                    <a href="#reviews" class="text-blue-600 hover:underline">
                        ({{ $product->reviews()->count() }} customer reviews)
                    </a>
                </div>

                <!-- Price Section -->
                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-4xl font-bold text-blue-600">
                            ${{ number_format($product->getDiscountedPriceAttribute(), 2) }}
                        </span>
                        @if ($product->discount_price)
                            <span class="text-2xl text-gray-400 line-through">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            <span class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold">
                                Save {{ $product->getDiscountPercentageAttribute() }}%
                            </span>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    @if ($product->stock > 0)
                        <div class="flex items-center gap-2 text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i>
                            {{ $product->stock }} in stock
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-red-600 font-semibold">
                            <i class="fas fa-times-circle"></i>
                            Out of stock
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div>
                    <h3 class="font-bold text-lg mb-3">Product Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Quantity & Actions -->
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <label class="font-semibold">Quantity:</label>
                        <div x-data="{ quantity: 1 }" class="flex items-center border rounded-lg">
                            <button @click="quantity = Math.max(1, quantity - 1)" class="px-4 py-2 text-gray-600 hover:bg-gray-100">−</button>
                            <input x-model.number="quantity" type="number" min="1" class="w-16 text-center border-none focus:outline-none" readonly>
                            <button @click="quantity += 1" class="px-4 py-2 text-gray-600 hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart & Wishlist -->
                    <div class="flex gap-4">
                        <button @if ($product->stock > 0) @else disabled @endif 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 disabled:bg-gray-400">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                        <button class="flex-1 border-2 border-blue-600 text-blue-600 py-3 rounded-lg font-bold hover:bg-blue-50 transition flex items-center justify-center gap-2">
                            <i class="fas fa-heart"></i>
                            Add to Wishlist
                        </button>
                    </div>
                </div>

                <!-- Shipping & Returns -->
                <div class="border-t pt-6 space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-1 text-center">
                            <i class="fas fa-truck text-blue-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Free Shipping</p>
                            <p class="text-sm text-gray-600">On orders over $50</p>
                        </div>
                        <div class="flex-1 text-center">
                            <i class="fas fa-undo text-blue-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Easy Returns</p>
                            <p class="text-sm text-gray-600">30-day return policy</p>
                        </div>
                        <div class="flex-1 text-center">
                            <i class="fas fa-lock text-blue-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Secure Payment</p>
                            <p class="text-sm text-gray-600">100% secure checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-20">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related as $relatedProduct)
                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="group">
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105">
                            <div class="bg-gradient-to-br from-blue-100 to-purple-100 h-48 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-5xl"></i>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xl font-bold text-blue-600">
                                        ${{ number_format($relatedProduct->getDiscountedPriceAttribute(), 2) }}
                                    </span>
                                </div>
                                <button class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-20" id="reviews">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Customer Reviews</h2>

            <!-- Review Form -->
            @auth
                <div class="bg-white rounded-lg p-6 mb-8 border-2 border-blue-100">
                    <h3 class="font-bold text-lg mb-4">Write a Review</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block font-semibold mb-2">Rating</label>
                            <div class="flex gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" class="hidden peer">
                                    <label for="rating{{ $i }}" class="cursor-pointer text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2">Comment</label>
                            <textarea name="comment" rows="4" placeholder="Share your experience with this product..." 
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Post Review
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <p class="text-gray-700">
                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Sign in</a>
                        to write a review
                    </p>
                </div>
            @endauth

            <!-- Reviews List -->
            <div class="space-y-6">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="font-semibold text-gray-900">John Doe</p>
                                <div class="flex items-center gap-2 mt-1">
                                    @for ($j = 0; $j < 5; $j++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="text-sm text-gray-500">{{ now()->subDays($i)->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <span class="text-green-600 text-sm font-semibold">✓ Verified Purchase</span>
                        </div>
                        <p class="text-gray-700">
                            This product is amazing! Great quality and fast delivery. Highly recommended to everyone.
                        </p>
                        <div class="mt-4 flex items-center gap-4 pt-4 border-t">
                            <button class="text-gray-600 hover:text-blue-600 transition text-sm">
                                <i class="fas fa-thumbs-up mr-1"></i> Helpful ({{ rand(5, 50) }})
                            </button>
                            <button class="text-gray-600 hover:text-red-600 transition text-sm">
                                <i class="fas fa-thumbs-down mr-1"></i> Unhelpful
                            </button>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
