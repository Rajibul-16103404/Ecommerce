@extends('layouts.app')

@section('title', 'Your Wishlist - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8">My Wishlist</h1>

        @if($wishlistItems->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="far fa-heart text-slate-350 text-3xl animate-pulse"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Your wishlist is empty</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto">Click the heart button on product cards to store items you plan to buy later.</p>
                <a href="{{ route('products.index') }}" class="mt-6 px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-2xl text-sm transition shadow-lg inline-block">
                    Explore Products
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition duration-300 group flex flex-col justify-between h-full relative">
                        
                        <!-- Remove from Wishlist Floating button -->
                        <form method="POST" action="{{ route('wishlist.remove', $product->id) }}" class="absolute top-4 right-4 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-full bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-500 shadow-md flex items-center justify-center transition">
                                <i class="fas fa-times text-xs"></i>
                              </button>
                        </form>

                        <div>
                            <!-- Product Image -->
                            <div class="relative bg-slate-50 h-52 flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <i class="fas fa-image text-slate-350 text-4xl"></i>
                                @endif
                                
                                @if($product->discount_price)
                                    <span class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="p-5">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $product->category->name }}</span>
                                <h3 class="font-bold text-slate-800 text-sm mt-0.5 line-clamp-1 group-hover:text-emerald-600 transition">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>

                                <div class="flex items-baseline space-x-2 mt-3">
                                    <span class="text-lg font-extrabold text-emerald-600">৳{{ number_format($product->discounted_price, 2) }}</span>
                                    @if ($product->discount_price)
                                        <span class="text-xs text-slate-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart & Buy Now -->
                        <div class="px-5 pb-5 pt-1">
                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="flex-1 py-2.5 bg-slate-900 hover:bg-emerald-650 text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center">
                                        Add to Cart
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="flex-1 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center">
                                        Buy Now
                                    </button>
                                </form>
                            @else
                                <button class="w-full py-2.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
