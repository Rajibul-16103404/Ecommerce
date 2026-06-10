@extends('layouts.app')

@section('title', $product->name . ' - ShopHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-500 mb-8 items-center space-x-2">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-indigo-600 transition">{{ $product->category->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-800 font-medium truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Gallery (AlpineJS driven) -->
            @php
                $images = collect([$product->image])->concat($product->productImages->pluck('image_url'))->filter();
            @endphp
            <div x-data="{ activeImage: '{{ $images->first() ?: 'placeholder' }}' }" class="lg:col-span-6 space-y-4">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-center min-h-[400px] overflow-hidden">
                    <template x-if="activeImage !== 'placeholder'">
                        <img :src="activeImage" alt="{{ $product->name }}" class="max-h-[350px] object-contain rounded-xl transform hover:scale-105 transition duration-500">
                    </template>
                    <template x-if="activeImage === 'placeholder'">
                        <i class="fas fa-image text-slate-300 text-7xl"></i>
                    </template>
                </div>
                
                @if($images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($images as $img)
                            <button @click="activeImage = '{{ $img }}'" 
                                class="w-20 h-20 bg-white rounded-xl border p-1 overflow-hidden transition focus:outline-none flex-shrink-0"
                                :class="activeImage === '{{ $img }}' ? 'border-indigo-600 ring-2 ring-indigo-100' : 'border-slate-100 hover:border-indigo-300'">
                                <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover rounded-lg">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details Panel -->
            <div class="lg:col-span-6 space-y-6">
                <div class="space-y-2">
                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center space-x-4 pt-1">
                        <div class="flex text-amber-400 text-sm">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < floor($product->average_rating) ? '' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-slate-500 font-semibold">{{ number_format($product->average_rating, 1) }} / 5.0</span>
                        <span class="text-slate-300 text-xs">|</span>
                        <a href="#reviews" class="text-sm text-indigo-600 font-semibold hover:underline">
                            {{ $product->reviews()->count() }} Customer Reviews
                        </a>
                    </div>
                </div>

                <div class="p-6 bg-slate-100/60 rounded-3xl space-y-4 border border-slate-200/40">
                    <div class="flex items-baseline space-x-3">
                        <span class="text-4xl font-black text-indigo-600">${{ number_format($product->discounted_price, 2) }}</span>
                        @if ($product->discount_price)
                            <span class="text-xl text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                            <span class="bg-rose-500 text-white text-xs font-bold px-3 py-1 rounded-xl uppercase">
                                Save {{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>

                    <div class="text-sm font-bold flex items-center">
                        @if($product->stock > 5)
                            <span class="text-green-600 flex items-center"><i class="fas fa-check-circle mr-2"></i> {{ $product->stock }} units in stock (Ready to Ship)</span>
                        @elseif($product->stock > 0)
                            <span class="text-orange-500 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Only {{ $product->stock }} left in stock!</span>
                        @else
                            <span class="text-rose-500 flex items-center"><i class="fas fa-times-circle mr-2"></i> Out of Stock</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2.5">
                    <h3 class="font-bold text-slate-900">Description</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Quantity adjustment & Add to Cart form -->
                <div x-data="{ qty: 1, maxQty: {{ $product->stock }} }" class="pt-4 border-t border-slate-100 space-y-5">
                    @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex items-center space-x-4">
                                <label class="text-sm font-bold text-slate-500">Select Quantity:</label>
                                <div class="flex items-center border border-slate-200 bg-white rounded-xl overflow-hidden">
                                    <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-4 py-2 hover:bg-slate-50 text-slate-600 font-bold focus:outline-none">−</button>
                                    <input type="number" name="quantity" :value="qty" readonly class="w-12 text-center border-0 focus:outline-none focus:ring-0 font-bold text-slate-800 text-sm">
                                    <button type="button" @click="qty = Math.min(maxQty, qty + 1)" class="px-4 py-2 hover:bg-slate-50 text-slate-600 font-bold focus:outline-none">+</button>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" class="flex-1 py-4 bg-slate-900 hover:bg-indigo-600 text-white font-bold rounded-2xl shadow-xl transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2.5"></i> Add to Shopping Cart
                                </button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('wishlist.add') }}" class="inline-block {{ $product->stock > 0 ? '' : 'w-full' }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full py-4 px-6 border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-bold rounded-2xl transition flex items-center justify-center gap-2">
                            <i class="far fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                    </div>
                </div>

                <!-- Support features -->
                <div class="grid grid-cols-3 gap-4 border-t border-slate-100 pt-6 text-center">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-truck text-indigo-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Free Shipping</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">On orders over $50</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-undo text-indigo-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Easy Returns</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">30-day money-back</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-shield-halved text-indigo-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Secure Payments</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Manual Txn verified</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="mt-24">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-8">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($related as $relatedProduct)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition duration-300 group flex flex-col justify-between h-full">
                        <div>
                            <div class="relative bg-slate-50 h-52 flex items-center justify-center overflow-hidden">
                                @if($relatedProduct->image)
                                    <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <i class="fas fa-image text-slate-300 text-4xl"></i>
                                @endif
                            </div>
                            <div class="p-5">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $relatedProduct->category->name }}</span>
                                <h3 class="font-bold text-slate-800 text-sm mt-0.5 line-clamp-1 group-hover:text-indigo-600">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                </h3>
                                <div class="flex items-baseline space-x-2 mt-3">
                                    <span class="text-lg font-extrabold text-indigo-600">${{ number_format($relatedProduct->discounted_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5 pt-1">
                            @if($relatedProduct->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition duration-200">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <button class="w-full py-2.5 bg-slate-100 text-slate-450 text-xs font-bold rounded-xl cursor-not-allowed" disabled>Out of Stock</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Customer Reviews Section -->
        <div class="mt-24 border-t border-slate-100 pt-16" id="reviews">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Review Form -->
                <div class="lg:col-span-5 space-y-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Customer Feedbacks</h2>
                    
                    @auth
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                            <h3 class="font-bold text-slate-900 text-base">Write a Product Review</h3>
                            
                            <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="space-y-4">
                                @csrf
                                
                                <div x-data="{ star: 5 }" class="space-y-2">
                                    <label class="block text-xs font-bold uppercase text-slate-400">Your Rating</label>
                                    <div class="flex gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden" :checked="star === {{ $i }}">
                                            <label for="star{{ $i }}" @click="star = {{ $i }}" class="cursor-pointer text-2xl transition" :class="star >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label for="comment" class="block text-xs font-bold uppercase text-slate-400 mb-2">Write Review Message</label>
                                    <textarea id="comment" name="comment" rows="4" placeholder="How was your experience using this product? Quality, sizing, shipping, etc..."
                                        class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none" required></textarea>
                                </div>

                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-indigo-50 border border-indigo-100 rounded-3xl p-6 text-center space-y-3">
                            <i class="fas fa-lock text-indigo-400 text-2xl"></i>
                            <h3 class="font-bold text-slate-800">Auth Protected Area</h3>
                            <p class="text-slate-500 text-xs max-w-xs mx-auto">Please login to submit product feedback and rating stars.</p>
                            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl inline-block mt-2">Login Now</a>
                        </div>
                    @endauth
                </div>

                <!-- Reviews Listing -->
                <div class="lg:col-span-7 space-y-6">
                    <h3 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4">Reviews History ({{ $product->reviews()->count() }})</h3>
                    
                    @php
                        $reviews = $product->reviews()->with('user')->orderBy('created_at', 'desc')->get();
                    @endphp

                    @if($reviews->isEmpty())
                        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="far fa-comments text-slate-350"></i>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">No reviews yet</h4>
                            <p class="text-slate-400 text-xs mt-1">Be the first to share your thoughts on this product.</p>
                        </div>
                    @else
                        <div class="space-y-4 max-h-[550px] overflow-y-auto pr-2">
                            @foreach($reviews as $review)
                                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-3 relative">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $review->user->name }}</p>
                                            <div class="flex items-center space-x-2.5 mt-1">
                                                <div class="flex text-amber-400 text-[10px]">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i class="fas fa-star {{ $i < $review->rating ? '' : 'text-slate-200' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-[10px] text-slate-400 font-semibold">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <span class="text-green-600 text-[10px] font-bold bg-green-50 px-2.5 py-0.5 rounded-full flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i> Verified Purchase
                                        </span>
                                    </div>
                                    <p class="text-slate-600 text-xs leading-relaxed">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
