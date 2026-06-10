@extends('layouts.app')

@section('title', 'Products Store - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters (Desktop) -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm sticky top-28 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 text-lg flex items-center">
                            <i class="fas fa-sliders-h mr-2.5 text-emerald-600"></i> Filters
                        </h3>
                        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-emerald-650 hover:text-emerald-750">Clear All</a>
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                        <!-- Search input -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Search Query</label>
                            <div class="relative">
                                <input type="text" name="search" placeholder="Type query..." 
                                    class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                    value="{{ request('search', '') }}">
                                <i class="fas fa-search absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                        </div>

                        <!-- Category select -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Category</label>
                            @php
                                $categories = \App\Models\Category::all();
                            @endphp
                            <select name="category" class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price range -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Price Bracket</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" placeholder="Min ৳" 
                                    class="w-full px-3 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                    value="{{ request('min_price', '') }}">
                                <input type="number" name="max_price" placeholder="Max ৳" 
                                    class="w-full px-3 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                    value="{{ request('max_price', '') }}">
                            </div>
                        </div>

                        <!-- Sorting -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Sort Results</label>
                            <select name="sort" class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none">
                                <option value="latest" @selected(request('sort') == 'latest' || !request('sort'))>Latest Additions</option>
                                <option value="popular" @selected(request('sort') == 'popular')>Most Popular</option>
                                <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low to High</option>
                                <option value="price_high" @selected(request('sort') == 'price_high')>Price: High to Low</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-emerald-50/50 flex items-center justify-center">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Products Output -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Explore Store</h1>
                        <p class="text-slate-500 text-sm mt-1">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} premium products</p>
                    </div>
                </div>

                @if ($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($products as $product)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition duration-300 hover-float group flex flex-col justify-between h-full">
                                <div>
                                    <!-- Image and Badge -->
                                    <div class="relative bg-slate-50 h-56 flex items-center justify-center overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                            <i class="fas fa-image text-slate-300 text-5xl"></i>
                                        @endif
                                        
                                        @if($product->discount_price)
                                            <span class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                                -{{ $product->discount_percentage }}%
                                            </span>
                                        @endif

                                        <div class="absolute bottom-4 right-4 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition duration-300 translate-y-2 group-hover:translate-y-0">
                                            <form method="POST" action="{{ route('wishlist.add') }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="w-10 h-10 rounded-full bg-white hover:bg-orange-50 text-slate-600 hover:text-orange-500 shadow-md flex items-center justify-center transition">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-6">
                                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $product->category->name }}</span>
                                        <h3 class="font-bold text-slate-800 mt-1 line-clamp-1 group-hover:text-emerald-600 transition">
                                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        <p class="text-slate-400 text-xs mt-1.5 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                        
                                        <!-- Rating -->
                                        <div class="flex items-center mt-3">
                                            <div class="flex text-amber-450 text-xs">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fas fa-star {{ $i < floor($product->average_rating) ? '' : 'text-slate-200' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text-slate-400 text-xs ml-2">({{ $product->reviews()->count() }})</span>
                                        </div>

                                        <!-- Stock status -->
                                        <div class="mt-3 text-xs font-bold">
                                            @if($product->stock > 5)
                                                <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> {{ $product->stock }} in stock</span>
                                            @elseif($product->stock > 0)
                                                <span class="text-orange-500"><i class="fas fa-exclamation-triangle mr-1"></i> Only {{ $product->stock }} left</span>
                                            @else
                                                <span class="text-rose-500"><i class="fas fa-times-circle mr-1"></i> Out of stock</span>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-baseline space-x-2 mt-4">
                                            <span class="text-2xl font-extrabold text-emerald-600">৳{{ number_format($product->discounted_price, 2) }}</span>
                                            @if ($product->discount_price)
                                                <span class="text-sm text-slate-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Add to Cart & Buy Now -->
                                <div class="px-6 pb-6 pt-2">
                                    @if($product->stock > 0)
                                        <form method="POST" action="{{ route('cart.add') }}" class="flex gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition duration-200 flex items-center justify-center shadow-md hover:shadow-emerald-50">
                                                Add to Cart
                                            </button>
                                            <button type="submit" name="buy_now" value="1" class="flex-1 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl text-xs transition duration-200 flex items-center justify-center shadow-sm">
                                                Buy Now
                                            </button>
                                        </form>
                                    @else
                                        <button class="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-sm cursor-not-allowed" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-slate-300 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No Products Found</h3>
                        <p class="text-slate-400 text-sm mt-1">Try updating your filters or search query.</p>
                        <a href="{{ route('products.index') }}" class="mt-4 px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl inline-block hover:bg-emerald-700 transition">Reset Search</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
