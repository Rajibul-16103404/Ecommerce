@extends('layouts.app')

@section('title', 'Shopee - Premium E-Commerce Store')

@section('content')
    <!-- Hero Carousel Section -->
    <div x-data="{ 
        activeSlide: 0,
        totalSlides: 3,
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
        },
        init() {
            setInterval(() => { this.next() }, 8000);
        }
    }" class="relative overflow-hidden bg-slate-950 text-white min-h-[580px] flex items-center">
        <!-- Floating Animated Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-600 rounded-full filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-20 right-20 w-96 h-96 bg-orange-500 rounded-full filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-20 left-1/3 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl opacity-15 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Carousel Slides -->
        <div class="w-full relative z-10 py-16 sm:py-20">
            
            <!-- Slide 1: General Promo / Smart Watch -->
            <div x-show="activeSlide === 0" 
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300 transform absolute inset-0 w-full"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 tracking-wider uppercase">
                            Summer Sale Live - Up to 40% Off
                        </span>
                        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                            Discover Your <br>
                            <span class="text-transparent bg-gradient-to-r from-emerald-450 via-amber-300 to-orange-450 bg-clip-text">
                                Perfect Lifestyle
                            </span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Shop premium products across categories: top-tier electronics, organic groceries, professional makeup, trendy fashion, and modern home essentials.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('products.index') }}" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/10 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                Shop Products <i class="fas fa-arrow-right ml-2.5"></i>
                            </a>
                            <a href="{{ route('categories.index') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition flex items-center justify-center">
                                Browse Categories
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 hidden lg:block relative">
                        <div class="relative w-full max-w-sm mx-auto aspect-square rounded-3xl bg-gradient-to-br from-emerald-500 to-orange-500 p-8 shadow-2xl overflow-hidden group">
                            <div class="absolute inset-0 bg-black/10 mix-blend-overlay group-hover:scale-105 transition duration-500"></div>
                            <div class="relative h-full flex flex-col justify-between text-white z-10">
                                <div>
                                    <h3 class="text-2xl font-bold">Smart Watch Pro</h3>
                                    <p class="text-emerald-150 text-sm mt-1">Premium Health Wearable</p>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-extrabold">$249.99</span>
                                    <span class="text-sm text-emerald-200 line-through">$299.99</span>
                                </div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600" alt="Smartwatch" 
                                class="absolute bottom-0 right-0 w-64 h-64 object-contain translate-x-10 translate-y-10 group-hover:scale-110 group-hover:translate-x-5 group-hover:translate-y-5 transition duration-500 ease-out">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Exquisite Chronograph Watch -->
            <div x-show="activeSlide === 1" 
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300 transform absolute inset-0 w-full"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20 tracking-wider uppercase">
                            Exclusive Timepiece Collection
                        </span>
                        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                            Timepieces of <br>
                            <span class="text-transparent bg-gradient-to-r from-orange-400 via-amber-300 to-emerald-450 bg-clip-text">
                                Pure Distinction
                            </span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Discover craftsmanship at its finest. Our curated chronographs offer unmatched luxury styling, automatic mechanical movements, and scratch-resistant sapphire glass.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('products.index') }}?search=watch" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                View Watches <i class="fas fa-clock ml-2.5"></i>
                            </a>
                            <a href="{{ route('products.index') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition flex items-center justify-center">
                                Explore Store
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 hidden lg:block relative">
                        <div class="relative w-full max-w-sm mx-auto aspect-square rounded-3xl bg-gradient-to-br from-orange-500 to-emerald-600 p-8 shadow-2xl overflow-hidden group">
                            <div class="absolute inset-0 bg-black/20 mix-blend-overlay group-hover:scale-105 transition duration-500"></div>
                            <div class="relative h-full flex flex-col justify-between text-white z-10">
                                <div>
                                    <h3 class="text-2xl font-bold">Chronograph Elite</h3>
                                    <p class="text-orange-100 text-sm mt-1">Handcrafted Premium Watch</p>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-extrabold">$349.00</span>
                                    <span class="text-sm text-orange-250 line-through">$450.00</span>
                                </div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600" alt="Premium Watch" 
                                class="absolute bottom-0 right-0 w-64 h-64 object-cover rounded-2xl translate-x-8 translate-y-8 group-hover:scale-110 group-hover:translate-x-3 group-hover:translate-y-3 transition duration-500 ease-out border-4 border-slate-900/60 shadow-xl">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Immersive Audio / Wireless Headphones -->
            <div x-show="activeSlide === 2" 
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300 transform absolute inset-0 w-full"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 tracking-wider uppercase">
                            High-Fidelity Audio Products
                        </span>
                        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                            Studio Sound, <br>
                            <span class="text-transparent bg-gradient-to-r from-emerald-450 via-teal-300 to-orange-450 bg-clip-text">
                                Anywhere You Go
                            </span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Experience crisp highs and resonant base. Our top-rated noise-cancelling wireless headphones deliver up to 40 hours of battery life on a single charge.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('products.index') }}" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                Buy Now <i class="fas fa-headphones ml-2.5"></i>
                            </a>
                            <a href="{{ route('products.index') }}?search=audio" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition flex items-center justify-center">
                                View Audio Gear
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 hidden lg:block relative">
                        <div class="relative w-full max-w-sm mx-auto aspect-square rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-500 p-8 shadow-2xl overflow-hidden group">
                            <div class="absolute inset-0 bg-black/10 mix-blend-overlay group-hover:scale-105 transition duration-500"></div>
                            <div class="relative h-full flex flex-col justify-between text-white z-10">
                                <div>
                                    <h3 class="text-2xl font-bold">Acoustic Ultra</h3>
                                    <p class="text-emerald-100 text-sm mt-1">Noise Cancelling Wireless</p>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-extrabold">$189.99</span>
                                    <span class="text-sm text-emerald-250 line-through">$249.99</span>
                                </div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600" alt="Audio Headphones" 
                                class="absolute bottom-0 right-0 w-64 h-64 object-contain translate-x-10 translate-y-10 group-hover:scale-110 group-hover:translate-x-5 group-hover:translate-y-5 transition duration-500 ease-out">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Left/Right Nav Arrows -->
        <button @click="prev()" class="absolute left-4 z-20 p-2 sm:p-3 rounded-full bg-slate-900/60 hover:bg-slate-800/80 text-white border border-slate-800/50 transition duration-300 focus:outline-none">
            <i class="fas fa-chevron-left text-sm sm:text-base"></i>
        </button>
        <button @click="next()" class="absolute right-4 z-20 p-2 sm:p-3 rounded-full bg-slate-900/60 hover:bg-slate-800/80 text-white border border-slate-800/50 transition duration-300 focus:outline-none">
            <i class="fas fa-chevron-right text-sm sm:text-base"></i>
        </button>

        <!-- Dots Indicators -->
        <div class="absolute bottom-6 left-0 right-0 z-20 flex justify-center space-x-2.5">
            <template x-for="index in totalSlides" :key="index">
                <button @click="activeSlide = index - 1" 
                        class="w-2.5 h-2.5 rounded-full transition-all duration-300 focus:outline-none"
                        :class="activeSlide === index - 1 ? 'bg-orange-500 w-8' : 'bg-slate-600 hover:bg-slate-500'"></button>
            </template>
        </div>
    </div>

    <!-- Category Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Shop by Category</h2>
            <p class="text-slate-500 mt-2.5">Discover our curated categories tailored for your specific lifestyle and requirements.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @php
                $categoriesList = [
                    ['icon' => 'fa-laptop-code', 'name' => 'Electronics', 'slug' => 'electronics', 'color' => 'from-emerald-500 to-emerald-600', 'shadow' => 'shadow-emerald-50'],
                    ['icon' => 'fa-apple-whole', 'name' => 'Grocery', 'slug' => 'grocery', 'color' => 'from-orange-500 to-orange-600', 'shadow' => 'shadow-orange-50'],
                    ['icon' => 'fa-wand-magic-sparkles', 'name' => 'Makeup', 'slug' => 'makeup', 'color' => 'from-emerald-600 to-teal-700', 'shadow' => 'shadow-emerald-50'],
                    ['icon' => 'fa-shirt', 'name' => 'Fashion', 'slug' => 'fashion', 'color' => 'from-orange-600 to-amber-600', 'shadow' => 'shadow-orange-50'],
                    ['icon' => 'fa-couch', 'name' => 'Home', 'slug' => 'home', 'color' => 'from-emerald-500 to-orange-550', 'shadow' => 'shadow-emerald-50'],
                ];
            @endphp

            @foreach ($categoriesList as $cat)
                <a href="{{ route('categories.show', $cat['slug']) }}" class="group">
                    <div class="bg-gradient-to-br {{ $cat['color'] }} rounded-2xl p-6 text-white text-center shadow-lg {{ $cat['shadow'] }} transform hover:-translate-y-2 transition duration-300 relative overflow-hidden h-full">
                        <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-white/10 rounded-full pointer-events-none group-hover:scale-150 transition duration-300"></div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition duration-300">
                            <i class="fas {{ $cat['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-lg">{{ $cat['name'] }}</h3>
                        <p class="text-xs text-white/80 mt-1 uppercase tracking-wider font-semibold">Explore Shop</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section class="bg-slate-100/50 py-20 border-t border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-16">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900">Featured Products</h2>
                    <p class="text-slate-500 mt-1">High quality items highly rated by our customers.</p>
                </div>
                <a href="{{ route('products.index') }}" class="mt-4 sm:mt-0 px-6 py-2.5 bg-white border border-slate-200 text-emerald-600 font-semibold rounded-xl text-sm hover:bg-slate-50 transition flex items-center">
                    View All Products <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($featured as $product)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition duration-300 group flex flex-col justify-between h-full">
                        <div>
                            <!-- Product Image & Overlay -->
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
                                
                                <!-- Rating -->
                                <div class="flex items-center mt-2.5">
                                    <div class="flex text-amber-450 text-xs">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star {{ $i < floor($product->average_rating) ? '' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-slate-400 text-xs ml-2">({{ $product->reviews()->count() }})</span>
                                </div>

                                <!-- Price -->
                                <div class="flex items-baseline space-x-2 mt-4">
                                    <span class="text-2xl font-extrabold text-emerald-600">${{ number_format($product->discounted_price, 2) }}</span>
                                    @if ($product->discount_price)
                                        <span class="text-sm text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart -->
                        <div class="px-6 pb-6 pt-2">
                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition duration-200 flex items-center justify-center shadow-md hover:shadow-emerald-50">
                                        <i class="fas fa-shopping-cart mr-2 text-xs"></i> Add to Cart
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
        </div>
    </section>

    <!-- Promotion Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="relative rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-950 to-orange-950 text-white overflow-hidden shadow-2xl py-16 px-8 sm:px-16 text-center lg:text-left flex flex-col lg:flex-row justify-between items-center gap-10 border border-emerald-500/10">
            <!-- Animated Background Blobs -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-orange-500 rounded-full filter blur-3xl opacity-15"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500 rounded-full filter blur-3xl opacity-15"></div>
            </div>

            <div class="space-y-4 max-w-xl z-10">
                <span class="text-orange-400 text-xs font-bold tracking-widest uppercase">Hot Promotional Deal</span>
                <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">Special Summer Sale!</h2>
                <p class="text-slate-400 text-sm sm:text-base">Enjoy a limited time reduction up to 40% on selected electronic devices and trendy streetwear clothing. Claim your special voucher.</p>
            </div>
            <div class="z-10 flex-shrink-0">
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="px-8 py-4 bg-white hover:bg-slate-100 text-slate-900 font-bold rounded-2xl shadow-xl transition transform hover:-translate-y-0.5 inline-block">
                    Discover Deals
                </a>
            </div>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="bg-slate-100/50 py-20 border-t border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-16">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900">Trending Products</h2>
                    <p class="text-slate-500 mt-1">See what others are looking at right now.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($trending as $product)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition duration-300 group flex flex-col justify-between h-full">
                        <div>
                            <!-- Product Image & Overlay -->
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
                                
                                <!-- Rating -->
                                <div class="flex items-center mt-2.5">
                                    <div class="flex text-amber-450 text-xs">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star {{ $i < floor($product->average_rating) ? '' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-slate-400 text-xs ml-2">({{ $product->reviews()->count() }})</span>
                                </div>

                                <!-- Price -->
                                <div class="flex items-baseline space-x-2 mt-4">
                                    <span class="text-2xl font-extrabold text-emerald-600">${{ number_format($product->discounted_price, 2) }}</span>
                                    @if ($product->discount_price)
                                        <span class="text-sm text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart -->
                        <div class="px-6 pb-6 pt-2">
                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition duration-200 flex items-center justify-center shadow-md hover:shadow-emerald-50">
                                        <i class="fas fa-shopping-cart mr-2 text-xs"></i> Add to Cart
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
        </div>
    </section>

    <!-- Support Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 border border-slate-100 flex items-start space-x-5 shadow-sm">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-650 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-truck text-2xl animate-pulse"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Fast Shipping</h3>
                    <p class="text-slate-500 text-sm mt-1">Free shipping on orders over $50. Prompt and secured delivery right to your door.</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-8 border border-slate-100 flex items-start space-x-5 shadow-sm">
                <div class="w-14 h-14 bg-orange-50 text-orange-650 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Manual Payment Verification</h3>
                    <p class="text-slate-500 text-sm mt-1">Manual payments with Mobile Banking or Direct Bank Transfer. Quick verification within hours.</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-8 border border-slate-100 flex items-start space-x-5 shadow-sm">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-650 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-900">24/7 Service Desk</h3>
                    <p class="text-slate-500 text-sm mt-1">Dedicated support agents available round the clock. Feel free to contact our desks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-slate-900 text-white py-20 relative overflow-hidden border-b border-slate-800">
        <div class="max-w-4xl mx-auto text-center px-4 z-10 relative space-y-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold">Stay Updated on Releases</h2>
            <p class="text-slate-400 max-w-xl mx-auto">Get exclusive emails on coupon codes, seasonal sales, and custom-tailored collection alerts.</p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto pt-4" onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
                <input type="email" placeholder="Enter your email address" class="flex-1 px-5 py-4 bg-slate-800 border-0 rounded-2xl text-white placeholder-slate-500 focus:bg-slate-850 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm" required>
                <button type="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm transition transform hover:-translate-y-0.5">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <!-- Tailwind keyframes declarations -->
    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        .animate-fade-in-right {
            animation: fadeInRight 0.6s ease-out forwards;
        }
    </style>
@endsection
