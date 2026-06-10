@extends('layouts.app')

@section('title', $product->name . ' - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-500 mb-8 items-center space-x-2">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Home</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-emerald-600 transition">{{ $product->category->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-800 font-medium truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Details Grid -->
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
                                :class="activeImage === '{{ $img }}' ? 'border-emerald-600 ring-2 ring-emerald-100' : 'border-slate-100 hover:border-emerald-300'">
                                <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover rounded-lg">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details Panel -->
            <div class="lg:col-span-6 space-y-6">
                <div class="space-y-2">
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center space-x-4 pt-1">
                        <div class="flex text-amber-450 text-sm">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < floor($product->average_rating) ? '' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-slate-500 font-semibold">{{ number_format($product->average_rating, 1) }} / 5.0</span>
                        <span class="text-slate-300 text-xs">|</span>
                        <span class="text-sm text-slate-500 font-semibold">
                            {{ $product->reviews()->count() }} Customer Reviews
                        </span>
                    </div>
                </div>

                <div class="p-6 bg-slate-100/60 rounded-3xl space-y-4 border border-slate-200/40">
                    <div class="flex items-baseline space-x-3">
                        <span class="text-4xl font-black text-emerald-600">৳{{ number_format($product->discounted_price, 2) }}</span>
                        @if ($product->discount_price)
                            <span class="text-xl text-slate-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                            <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-xl uppercase">
                                Save {{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>

                    <div class="text-sm font-bold flex items-center">
                        @if($product->stock > 5)
                            <span class="text-green-600 flex items-center"><i class="fas fa-check-circle mr-2"></i> {{ $product->stock }} units in stock (Ready to Ship)</span>
                        @elseif($product->stock > 0)
                            <span class="text-orange-505 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Only {{ $product->stock }} left in stock!</span>
                        @else
                            <span class="text-rose-500 flex items-center"><i class="fas fa-times-circle mr-2"></i> Out of Stock</span>
                        @endif
                    </div>
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
                                    <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-4 py-2 hover:bg-slate-50 text-slate-655 font-bold focus:outline-none">−</button>
                                    <input type="number" name="quantity" :value="qty" readonly class="w-12 text-center border-0 focus:outline-none focus:ring-0 font-bold text-slate-800 text-sm">
                                    <button type="button" @click="qty = Math.min(maxQty, qty + 1)" class="px-4 py-2 hover:bg-slate-50 text-slate-655 font-bold focus:outline-none">+</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-750 text-white font-bold rounded-2xl transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2.5"></i> Add to Cart
                                </button>
                                <button type="submit" name="buy_now" value="1" class="w-full py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-2xl shadow-xl shadow-orange-500/5 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-bolt mr-2.5"></i> Buy Now
                                </button>
                            </div>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('wishlist.add') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full py-4 px-6 border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-bold rounded-2xl transition flex items-center justify-center gap-2">
                            <i class="far fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                </div>

                <!-- Support features -->
                <div class="grid grid-cols-3 gap-4 border-t border-slate-100 pt-6 text-center">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-truck text-emerald-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Shipping</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Based on location</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-undo text-emerald-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Easy Returns</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">30-day money-back</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fas fa-shield-halved text-emerald-600 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-slate-800">Secure Payments</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Manual Txn verified</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout Change: AlpineJS driven tabbed Description / Specs / Reviews -->
        <div x-data="{ activeTab: 'description' }" class="mt-20 border border-slate-150 bg-white rounded-3xl overflow-hidden shadow-sm">
            <!-- Tab Headers -->
            <div class="flex border-b border-slate-150 bg-slate-50">
                <button @click="activeTab = 'description'"
                    class="px-8 py-5 text-sm font-bold border-b-2 focus:outline-none transition"
                    :class="activeTab === 'description' ? 'border-emerald-600 text-emerald-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'">
                    Description & Specifications
                </button>
                <button @click="activeTab = 'reviews'"
                    class="px-8 py-5 text-sm font-bold border-b-2 focus:outline-none transition"
                    :class="activeTab === 'reviews' ? 'border-emerald-600 text-emerald-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'">
                    Customer Reviews ({{ $product->reviews()->count() }})
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="space-y-6 text-sm text-slate-650 leading-relaxed">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 mb-2">Detailed Product Specifications</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                        <div class="flex justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-450 font-medium">Category</span>
                            <span class="font-bold text-slate-800">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-450 font-medium">Stock Status</span>
                            <span class="font-bold text-slate-800">{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                        </div>
                        <div class="flex justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-450 font-medium">Standard Price</span>
                            <span class="font-bold text-slate-800">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-450 font-medium">Verified Vendor</span>
                            <span class="font-bold text-slate-800">{{ $product->vendor->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" class="space-y-8" style="display: none;">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                        <!-- Review Form -->
                        <div class="lg:col-span-5 space-y-6">
                            @auth
                                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 space-y-5">
                                    <h3 class="font-bold text-slate-900 text-sm">Write a Product Review</h3>
                                    
                                    <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="space-y-4">
                                        @csrf
                                        
                                        <div x-data="{ star: 5 }" class="space-y-2">
                                            <label class="block text-xs font-bold uppercase text-slate-450">Your Rating</label>
                                            <div class="flex gap-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden" :checked="star === {{ $i }}">
                                                    <label for="star{{ $i }}" @click="star = {{ $i }}" class="cursor-pointer text-2xl transition" :class="star >= {{ $i }} ? 'text-amber-450' : 'text-slate-200'">
                                                        <i class="fas fa-star"></i>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div>
                                            <label for="comment" class="block text-xs font-bold uppercase text-slate-450 mb-2">Write Review Message</label>
                                            <textarea id="comment" name="comment" rows="4" placeholder="How was your experience using this product?"
                                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none" required></textarea>
                                        </div>

                                        <button type="submit" class="w-full py-3 bg-emerald-650 hover:bg-emerald-750 text-white font-bold rounded-xl text-sm transition">
                                            Submit Review
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 text-center space-y-3">
                                    <i class="fas fa-lock text-emerald-400 text-xl"></i>
                                    <h3 class="font-bold text-slate-800 text-sm">Auth Protected Area</h3>
                                    <p class="text-slate-500 text-[11px] max-w-xs mx-auto">Please login to submit product feedback and rating stars.</p>
                                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-705 text-white text-xs font-bold rounded-xl inline-block mt-2">Login Now</a>
                                </div>
                            @endauth
                        </div>

                        <!-- Reviews Listing -->
                        <div class="lg:col-span-7 space-y-6">
                            @php
                                $reviews = $product->reviews()->with('user')->orderBy('created_at', 'desc')->get();
                            @endphp

                            @if($reviews->isEmpty())
                                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-200">
                                        <i class="far fa-comments text-slate-350"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-850 text-xs">No reviews yet</h4>
                                    <p class="text-slate-400 text-[10px] mt-1">Be the first to share your thoughts on this product.</p>
                                </div>
                            @else
                                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                                    @foreach($reviews as $review)
                                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 space-y-3">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $review->user->name }}</p>
                                                    <div class="flex items-center space-x-2.5 mt-1">
                                                        <div class="flex text-amber-450 text-[9px]">
                                                            @for ($i = 0; $i < 5; $i++)
                                                                <i class="fas fa-star {{ $i < $review->rating ? '' : 'text-slate-200' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="text-[9px] text-slate-400 font-semibold">{{ $review->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                </div>
                                                <span class="text-green-600 text-[9px] font-bold bg-green-50 px-2 rounded-full">
                                                    ✓ Verified Purchase
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
                                <h3 class="font-bold text-slate-800 text-sm mt-0.5 line-clamp-1 group-hover:text-emerald-600">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                </h3>
                                <div class="flex items-baseline space-x-2 mt-3">
                                    <span class="text-lg font-extrabold text-emerald-650">৳{{ number_format($relatedProduct->discounted_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5 pt-1">
                            @if($relatedProduct->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="flex-1 py-2 bg-emerald-605 hover:bg-emerald-705 text-white text-[10px] font-bold rounded-xl transition">
                                        Add to Cart
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="flex-1 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-[10px] font-bold rounded-xl transition">
                                        Buy Now
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

    </div>
@endsection
