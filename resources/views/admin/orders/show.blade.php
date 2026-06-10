@extends('layouts.admin')

@section('title', 'Verify Order ' . $order->order_number . ' - ShopHub')
@section('page_title', 'Review Order')

@section('content')
    <div class="space-y-6 max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-800">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-400 hover:underline flex items-center mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Orders
                </a>
                <h1 class="text-2xl font-black text-white tracking-tight">Reviewing Order: <span class="font-mono text-slate-500 font-medium">{{ $order->order_number }}</span></h1>
            </div>
            
            <div class="flex items-center space-x-3 text-xs">
                <span class="text-slate-500">Date Placed: {{ $order->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Order Items and Verification Details -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Manual payment verification card -->
                @if($order->payment_method !== 'cod')
                    <div class="bg-indigo-950/40 border border-indigo-900/40 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
                        <h3 class="font-extrabold text-indigo-400 text-base flex items-center">
                            <i class="fas fa-credit-card mr-2 text-sm"></i> Manual Payment Verification
                        </h3>
                        <p class="text-xs text-slate-400">Verify this transaction by cross-referencing your mobile banking wallet or bank statement using the reference codes below.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div class="p-4 bg-slate-900 border border-slate-850 rounded-2xl">
                                <span class="block text-[10px] text-slate-500 font-bold uppercase tracking-wide">Method Selected</span>
                                <span class="font-bold text-slate-200 text-sm uppercase mt-0.5 block">{{ $order->payment_method }}</span>
                            </div>
                            <div class="p-4 bg-slate-900 border border-slate-850 rounded-2xl">
                                <span class="block text-[10px] text-slate-500 font-bold uppercase tracking-wide">Sender Account / Phone</span>
                                <span class="font-bold text-slate-200 text-sm mt-0.5 block font-mono">{{ $order->payment_sender ?: 'N/A' }}</span>
                            </div>
                            <div class="p-4 bg-slate-900 border border-slate-850 rounded-2xl">
                                <span class="block text-[10px] text-slate-500 font-bold uppercase tracking-wide">Transaction ID (TxnID)</span>
                                <span class="font-bold text-indigo-400 text-sm mt-0.5 block font-mono select-all">{{ $order->transaction_id ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Items list -->
                <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <h3 class="font-extrabold text-white text-base border-b border-slate-850 pb-3">Products Purchased</h3>
                    
                    <div class="divide-y divide-slate-850">
                        @foreach($order->orderItems as $item)
                            <div class="flex py-4 items-center justify-between text-xs">
                                <div class="flex items-center">
                                    <div class="w-14 h-14 bg-slate-900 border border-slate-850 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-slate-650 text-base"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-bold text-slate-200 text-sm line-clamp-1">{{ $item->product->name }}</h4>
                                        <p class="text-[10px] text-slate-500 mt-1">
                                            <span>Price: ${{ number_format($item->price, 2) }}</span>
                                            <span class="mx-1.5">|</span>
                                            <span>Quantity: {{ $item->quantity }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-200 text-sm">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-850 pt-5 space-y-3 text-xs font-semibold max-w-xs ml-auto">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="text-slate-300">
                                ${{ number_format($order->total_price > 50 ? $order->total_price : $order->total_price - 9.99, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Shipping Costs</span>
                            <span class="text-slate-300">
                                @if($order->total_price > 50)
                                    <span class="text-green-400 font-bold">FREE</span>
                                @else
                                    $9.99
                                @endif
                            </span>
                        </div>
                        <hr class="border-slate-850">
                        <div class="flex justify-between text-sm font-bold text-white">
                            <span>Grand Total</span>
                            <span class="text-indigo-400">${{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Status Action controls and Shipping addresses -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Action Dropdowns -->
                <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <h3 class="font-extrabold text-white text-base border-b border-slate-850 pb-3">Update Order Status</h3>

                    <!-- Verify Payment Form -->
                    <form method="POST" action="{{ route('admin.orders.update-payment', $order->id) }}" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <label for="payment_status" class="block text-[10px] font-bold uppercase text-slate-500">Payment Verification</label>
                        <div class="flex gap-2">
                            <select id="payment_status" name="payment_status" 
                                class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg focus:border-indigo-500 text-xs focus:outline-none text-slate-100">
                                <option value="pending" @selected($order->payment_status === 'pending')>Pending</option>
                                <option value="pending_verification" @selected($order->payment_status === 'pending_verification')>Pending Verification</option>
                                <option value="paid" @selected($order->payment_status === 'paid')>Verified / Paid</option>
                                <option value="failed" @selected($order->payment_status === 'failed')>Failed / Declined</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-750 text-white font-bold text-xs rounded-lg transition">Save</button>
                        </div>
                    </form>

                    <!-- Shipping Progress Form -->
                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <label for="status" class="block text-[10px] font-bold uppercase text-slate-500">Delivery Status</label>
                        <div class="flex gap-2">
                            <select id="status" name="status" 
                                class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg focus:border-indigo-500 text-xs focus:outline-none text-slate-100">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-750 text-white font-bold text-xs rounded-lg transition">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Shipping details info -->
                <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm text-xs">
                    <h3 class="font-extrabold text-white text-base border-b border-slate-850 pb-3">Delivery Address</h3>
                    <div class="text-slate-400 space-y-1.5 leading-relaxed">
                        <p class="font-bold text-slate-200 text-sm">{{ $order->user->name }}</p>
                        <p class="text-slate-500 font-semibold">{{ $order->user->email }}</p>
                        <hr class="border-slate-850 my-2">
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
                        <p class="pt-2 flex items-center font-bold text-slate-300">
                            <i class="fas fa-phone mr-2 text-slate-500 text-[10px]"></i> {{ $order->shipping_phone }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
