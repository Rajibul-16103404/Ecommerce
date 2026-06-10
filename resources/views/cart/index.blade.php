@extends('layouts.app')

@section('title', 'Your Shopping Cart - ShopHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8">Shopping Cart</h1>

        @if($cartItems->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-slate-300 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Your cart is currently empty</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto">Fill it with high-quality clothing, gadgets, cosmetics, and groceries from our premium collections.</p>
                <a href="{{ route('products.index') }}" class="mt-6 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm transition shadow-lg shadow-indigo-100 inline-block">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Items list -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6">
                    <div class="hidden sm:grid grid-cols-12 gap-4 pb-4 border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                        <div class="col-span-6">Product Details</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($cartItems as $item)
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 py-6 first:pt-0 last:pb-0 items-center">
                                
                                <!-- Product Details -->
                                <div class="col-span-12 sm:col-span-6 flex items-center">
                                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-slate-350 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-bold text-slate-800 text-sm line-clamp-2 hover:text-indigo-600 transition">
                                            <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        </h3>
                                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider">{{ $item->product->category->name }}</p>
                                        <form method="POST" action="{{ route('cart.remove', $item->product->id) }}" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-600 flex items-center">
                                                <i class="far fa-trash-can mr-1.5"></i> Remove Item
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Unit Price -->
                                <div class="col-span-4 sm:col-span-2 text-left sm:text-center">
                                    <span class="sm:hidden text-xs text-slate-400 font-bold uppercase block">Price</span>
                                    <span class="font-bold text-slate-800 text-sm">${{ number_format($item->product->discounted_price, 2) }}</span>
                                </div>

                                <!-- Quantity adjuster -->
                                <div class="col-span-4 sm:col-span-2 flex justify-start sm:justify-center">
                                    <div class="flex flex-col items-center">
                                        <span class="sm:hidden text-xs text-slate-400 font-bold uppercase block mb-1">Quantity</span>
                                        <form method="POST" action="{{ route('cart.update', $item->product->id) }}" class="flex items-center border border-slate-200 bg-white rounded-lg overflow-hidden">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="px-2.5 py-1 hover:bg-slate-50 text-slate-600 font-bold text-xs">−</button>
                                            <span class="px-3 font-bold text-slate-800 text-xs">{{ $item->quantity }}</span>
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-2.5 py-1 hover:bg-slate-50 text-slate-600 font-bold text-xs">+</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Total Item Price -->
                                <div class="col-span-4 sm:col-span-2 text-right">
                                    <span class="sm:hidden text-xs text-slate-400 font-bold uppercase block">Total</span>
                                    <span class="font-bold text-indigo-650 text-base">${{ number_format($item->product->discounted_price * $item->quantity, 2) }}</span>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
                    <h3 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4">Order Summary</h3>

                    <div class="space-y-4 text-sm font-medium">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="text-slate-800">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Shipping Costs</span>
                            <span class="text-slate-800">
                                @if($shipping == 0)
                                    <span class="text-green-600 font-bold">FREE</span>
                                @else
                                    ${{ number_format($shipping, 2) }}
                                @endif
                            </span>
                        </div>
                        @if($shipping > 0)
                            <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100/50 text-xs text-indigo-700 leading-normal">
                                <i class="fas fa-circle-info mr-1.5"></i> Add <span class="font-bold">${{ number_format(50 - $subtotal, 2) }}</span> more to unlock <span class="font-bold">FREE SHIPPING</span>!
                            </div>
                        @endif
                        <hr class="border-slate-100">
                        <div class="flex justify-between text-base font-bold text-slate-900">
                            <span>Estimated Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('checkout') }}" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/10 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                            Proceed to Checkout <i class="fas fa-credit-card ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
