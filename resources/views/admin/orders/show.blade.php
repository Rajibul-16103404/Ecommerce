@extends('layouts.admin')

@section('title', 'Verify Order ' . $order->order_number . ' - Shopee')
@section('page_title', 'Review Order')

@section('content')
    <div class="space-y-6 max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-emerald-600 hover:underline flex items-center mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Orders
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Reviewing Order: <span class="font-mono text-slate-400 font-medium">{{ $order->order_number }}</span></h1>
            </div>
            
            <div class="flex items-center space-x-3 text-xs">
                <span class="text-slate-400">Date Placed: {{ $order->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Order Items and Verification Details -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Manual payment verification card -->
                @if($order->payment_method !== 'cod')
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
                        <h3 class="font-extrabold text-emerald-700 text-base flex items-center">
                            <i class="fas fa-credit-card mr-2 text-sm text-orange-500"></i> Manual Payment Verification
                        </h3>
                        <p class="text-xs text-slate-500">Verify this transaction by cross-referencing your mobile banking wallet or bank statement using the reference codes below.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div class="p-4 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wide">Method Selected</span>
                                <span class="font-bold text-slate-800 text-sm uppercase mt-0.5 block">{{ $order->payment_method }}</span>
                            </div>
                            <div class="p-4 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wide">Sender Account / Phone</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block font-mono">{{ $order->payment_sender ?: 'N/A' }}</span>
                            </div>
                            <div class="p-4 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wide">Transaction ID (TxnID)</span>
                                <span class="font-bold text-orange-600 text-sm mt-0.5 block font-mono select-all">{{ $order->transaction_id ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Items list -->
                <div class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Products Purchased</h3>
                    
                    <div class="divide-y divide-slate-100">
                        @foreach($order->orderItems as $item)
                            <div class="flex py-4 items-center justify-between text-xs">
                                class="item-wrapper"
                                <div class="flex items-center">
                                    <div class="w-14 h-14 bg-slate-50 border border-slate-150 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-slate-300 text-base"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-bold text-slate-850 text-sm line-clamp-1">{{ $item->product->name }}</h4>
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            <span>Price: ৳{{ number_format($item->price, 2) }}</span>
                                            <span class="mx-1.5">|</span>
                                            <span>Quantity: {{ $item->quantity }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-850 text-sm">৳{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $orderSubtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                        $orderShipping = $order->total_price - $orderSubtotal;
                    @endphp
                    <div class="border-t border-slate-100 pt-5 space-y-3 text-xs font-semibold max-w-xs ml-auto">
                        <div class="flex justify-between text-slate-450">
                            <span>Subtotal</span>
                            <span class="text-slate-700">
                                ৳{{ number_format($orderSubtotal, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-450">
                            <span>Shipping Costs</span>
                            <span class="text-slate-700">
                                @if($orderShipping <= 0)
                                    <span class="text-green-600 font-bold">FREE</span>
                                @else
                                    ৳{{ number_format($orderShipping, 2) }}
                                @endif
                            </span>
                        </div>
                        <hr class="border-slate-100">
                        <div class="flex justify-between text-sm font-bold text-slate-900">
                            <span>Grand Total</span>
                            <span class="text-emerald-605">৳{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Status Action controls and Shipping addresses -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Action Dropdowns -->
                <div class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Update Order Status</h3>

                    <!-- Verify Payment Form -->
                    <form method="POST" action="{{ route('admin.orders.update-payment', $order->id) }}" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <label for="payment_status" class="block text-[10px] font-bold uppercase text-slate-400">Payment Verification</label>
                        <div class="flex gap-2">
                            <select id="payment_status" name="payment_status" 
                                class="flex-1 px-3 py-2 bg-slate-50 border border-slate-250/80 rounded-lg focus:bg-white focus:border-emerald-500 text-xs focus:outline-none text-slate-800">
                                <option value="pending" @selected($order->payment_status === 'pending')>Pending</option>
                                <option value="pending_verification" @selected($order->payment_status === 'pending_verification')>Pending Verification</option>
                                <option value="paid" @selected($order->payment_status === 'paid')>Verified / Paid</option>
                                <option value="failed" @selected($order->payment_status === 'failed')>Failed / Declined</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-md shadow-emerald-500/10">Save</button>
                        </div>
                    </form>

                    <!-- Shipping Progress Form -->
                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <label for="status" class="block text-[10px] font-bold uppercase text-slate-400">Delivery Status</label>
                        <div class="flex gap-2">
                            <select id="status" name="status" 
                                class="flex-1 px-3 py-2 bg-slate-50 border border-slate-250/80 rounded-lg focus:bg-white focus:border-emerald-500 text-xs focus:outline-none text-slate-800">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-md shadow-emerald-500/10">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Shipping details info -->
                <div class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm text-xs">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-truck text-emerald-500"></i> Delivery Address
                    </h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed">

                        <div>
                            <p class="font-bold text-slate-800 text-sm">{{ $order->user->name }}</p>
                            <p class="text-slate-400 font-semibold">{{ $order->user->email }}</p>
                        </div>

                        <hr class="border-slate-100">

                        <div class="grid grid-cols-1 gap-2.5">
                            <div>
                                <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Street Address</p>
                                <p class="font-medium text-slate-700">{{ $order->shipping_address }}</p>
                            </div>

                            @if($order->shipping_area)
                            <div>
                                <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Area / Neighbourhood</p>
                                <p class="font-medium text-slate-700">{{ $order->shipping_area }}</p>
                            </div>
                            @endif

                            @if($order->shipping_landmark)
                            <div>
                                <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Nearest Landmark</p>
                                <p class="font-medium text-slate-700">{{ $order->shipping_landmark }}</p>
                            </div>
                            @endif

                            <div class="flex gap-5">
                                <div>
                                    <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">City</p>
                                    <p class="font-bold text-slate-700">{{ $order->shipping_city }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">ZIP</p>
                                    <p class="font-bold text-slate-700">{{ $order->shipping_zip }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-0.5">
                                <i class="fas fa-phone text-emerald-500 text-[10px]"></i>
                                <span class="font-bold text-slate-700">{{ $order->shipping_phone }}</span>
                            </div>
                        </div>

                        @if($order->shipping_notes)
                        <div class="mt-1 p-3 bg-amber-50 border border-amber-100 rounded-xl">
                            <p class="text-[9px] uppercase tracking-wider text-amber-600 font-bold mb-1 flex items-center gap-1">
                                <i class="fas fa-note-sticky"></i> Delivery Instructions
                            </p>
                            <p class="text-amber-800 leading-relaxed">{{ $order->shipping_notes }}</p>
                        </div>
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
