@extends('layouts.app')

@section('title', 'Products - ShopHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-64 hidden lg:block">
                <div class="bg-white rounded-lg p-6 shadow-md sticky top-24">
                    <h3 class="font-bold text-lg mb-4">Filters</h3>

                    <!-- Search -->
                    <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Search</label>
                            <input type="text" name="search" placeholder="Search..." 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ request('search', '') }}">
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Category</label>
                            <select name="category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                <option value="1" @selected(request('category') == '1')>Electronics</option>
                                <option value="2" @selected(request('category') == '2')>Grocery</option>
                                <option value="3" @selected(request('category') == '3')>Makeup</option>
                                <option value="4" @selected(request('category') == '4')>Fashion</option>
                                <option value="5" @selected(request('category') == '5')>Home</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Price Range</label>
                            <div class="space-y-2">
                                <input type="number" name="min_price" placeholder="Min" 
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ request('min_price', '') }}">
                                <input type="number" name="max_price" placeholder="Max" 
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ request('max_price', '') }}">
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Sort By</label>
                            <select name="sort" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="latest" @selected(request('sort') == 'latest' || !request('sort'))>Latest</option>
                                <option value="popular" @selected(request('sort') == 'popular')>Popular</option>
                                <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low to High</option>
                                <option value="price_high" @selected(request('sort') == 'price_high')>Price: High to Low</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-8 text-gray-900">Products</h1>

                @if ($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @foreach ($products as $product)
                            <a href="{{ route('products.show', $product->slug) }}" class="group">
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 h-full">
                                    <!-- Product Image -->
                                    <div class="relative bg-gradient-to-br from-blue-100 to-purple-100 h-48 flex items-center justify-center overflow-hidden">
                                        <i class="fas fa-image text-gray-400 text-5xl"></i>
                                        @if ($product->discount_price)
                                            <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                                -{{ $product->getDiscountPercentageAttribute() }}%
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4 flex flex-col h-full">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600">{{ $product->name }}</h3>
                                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>

                                            <!-- Rating -->
                                            <div class="flex items-center mb-3">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                @endfor
                                                <span class="text-gray-600 text-xs ml-2">({{ $product->reviews()->count() }})</span>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-center gap-2 mb-4">
                                            <span class="text-2xl font-bold text-blue-600">
                                                ${{ number_format($product->getDiscountedPriceAttribute(), 2) }}
                                            </span>
                                            @if ($product->discount_price)
                                                <span class="text-sm text-gray-400 line-through">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        @if ($product->stock > 0)
                                            <span class="text-xs text-green-600 font-semibold mb-3">
                                                {{ $product->stock }} in stock
                                            </span>
                                        @else
                                            <span class="text-xs text-red-600 font-semibold mb-3">
                                                Out of stock
                                            </span>
                                        @endif

                                        <!-- Add to Cart Button -->
                                        <button class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 group-hover:scale-105">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-xl">No products found. Try adjusting your filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
