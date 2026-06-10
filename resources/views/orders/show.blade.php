@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <a href="{{ route('orders.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition flex items-center mb-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center">
                    Order Details: <span class="ml-2 font-mono text-slate-500 font-medium">{{ $order->order_number }}</span>
                </h1>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Status Badge -->
                @if($order->status === 'delivered')
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-150">Delivered</span>
                @elseif($order->status === 'shipped')
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-150">Shipped</span>
                @elseif($order->status === 'processing')
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-150">Processing</span>
                @elseif($order->status === 'cancelled')
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-150">Cancelled</span>
                @else
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Pending Approval</span>
                @endif
            </div>
        </div>

        <!-- Order Timeline Tracking -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mb-8">
            <h3 class="font-extrabold text-slate-800 text-base mb-8">Delivery Progress Timeline</h3>
            <div class="relative flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Connector Line -->
                <div class="absolute hidden md:block top-1/2 left-10 right-10 h-0.5 bg-slate-100 -translate-y-1/2 z-0"></div>

                @php
                    $stages = [
                        ['name' => 'Pending Approval', 'icon' => 'fa-clipboard-list', 'active' => true],
                        ['name' => 'Processing', 'icon' => 'fa-cog', 'active' => in_array($order->status, ['processing', 'shipped', 'delivered'])],
                        ['name' => 'Shipped', 'icon' => 'fa-truck', 'active' => in_array($order->status, ['shipped', 'delivered'])],
                        ['name' => 'Delivered', 'icon' => 'fa-circle-check', 'active' => $order->status === 'delivered'],
                    ];
                @endphp

                @foreach($stages as $stage)
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center border shadow-sm transition duration-300"
                            class="active"
                            :class=""
                            style="background-color: {{ $stage['active'] ? '#10b981' : '#f8fafc' }}; border-color: {{ $stage['active'] ? '#10b981' : '#e2e8f0' }}; color: {{ $stage['active'] ? '#ffffff' : '#94a3b8' }};">
                            <i class="fas {{ $stage['icon'] }} text-lg"></i>
                        </div>
                        <span class="text-xs font-bold mt-3" style="color: {{ $stage['active'] ? '#10b981' : '#94a3b8' }}">{{ $stage['name'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Items bought list -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
                    <h3 class="font-extrabold text-slate-800 text-base border-b border-slate-100 pb-3">Purchased Products</h3>
                    
                    <div class="divide-y divide-slate-100">
                        @foreach($order->orderItems as $item)
                            <div class="flex py-4 items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-slate-300"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-bold text-slate-800 text-sm line-clamp-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-emerald-600 transition">{{ $item->product->name }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-400 mt-1">
                                            <span>Unit Price: ৳{{ number_format($item->price, 2) }}</span>
                                            <span class="mx-1.5">|</span>
                                            <span>Qty: {{ $item->quantity }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-800 text-sm">৳{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $orderSubtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                        $orderShipping = $order->total_price - $orderSubtotal;
                    @endphp
                    <div class="border-t border-slate-100 pt-5 space-y-3 text-sm font-medium max-w-sm ml-auto">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="text-slate-800">
                                ৳{{ number_format($orderSubtotal, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Shipping Costs</span>
                            <span class="text-slate-800">
                                @if($orderShipping <= 0)
                                    <span class="text-green-600 font-bold">FREE</span>
                                @else
                                    ৳{{ number_format($orderShipping, 2) }}
                                @endif
                            </span>
                        </div>
                        <hr class="border-slate-100">
                        <div class="flex justify-between text-base font-bold text-slate-900">
                            <span>Order Total</span>
                            <span>৳{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing & Shipping detail cards -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Shipping Details -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-4">
                    <h3 class="font-extrabold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-truck text-emerald-500"></i> Shipping Address
                    </h3>
                    <div class="text-sm text-slate-600 space-y-3">

                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Recipient</p>
                            <p class="font-bold text-slate-800">{{ $order->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Street Address</p>
                            <p>{{ $order->shipping_address }}</p>
                        </div>

                        @if($order->shipping_area)
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Area / Neighbourhood</p>
                            <p>{{ $order->shipping_area }}</p>
                        </div>
                        @endif

                        @if($order->shipping_landmark)
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Nearest Landmark</p>
                            <p>{{ $order->shipping_landmark }}</p>
                        </div>
                        @endif

                        <div class="flex gap-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">City</p>
                                <p class="font-semibold text-slate-700">{{ $order->shipping_city }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">ZIP Code</p>
                                <p class="font-semibold text-slate-700">{{ $order->shipping_zip }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <i class="fas fa-phone text-xs text-emerald-500 flex-shrink-0"></i>
                            <span class="font-semibold text-slate-700">{{ $order->shipping_phone }}</span>
                        </div>

                        @if($order->shipping_notes)
                        <div class="mt-2 p-3 bg-amber-50 border border-amber-100 rounded-xl">
                            <p class="text-[10px] uppercase tracking-wider text-amber-600 font-bold mb-1 flex items-center gap-1">
                                <i class="fas fa-note-sticky"></i> Delivery Instructions
                            </p>
                            <p class="text-xs text-amber-800 leading-relaxed">{{ $order->shipping_notes }}</p>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-4">
                    <h3 class="font-extrabold text-slate-800 text-base border-b border-slate-100 pb-3">Payment Info</h3>
                    <div class="space-y-4">
                        <div class="text-sm text-slate-600 space-y-1">
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Payment Method</p>
                            <p class="font-bold text-slate-800 uppercase text-xs">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                        </div>
                        
                        <div class="text-sm text-slate-600 space-y-1">
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Verification Status</p>
                            <div>
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 font-bold rounded-lg text-xs border border-green-150">✓ Approved & Verified</span>
                                @elseif($order->payment_status === 'pending_verification')
                                    <span class="inline-flex items-center px-3 py-1 bg-orange-50 text-orange-700 font-bold rounded-lg text-xs border border-orange-150">⏳ Pending Admin Verification</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="inline-flex items-center px-3 py-1 bg-red-50 text-red-700 font-bold rounded-lg text-xs border border-red-150">✗ Transaction Declined</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 font-bold rounded-lg text-xs">⏳ Awaiting Payment</span>
                                @endif
                            </div>
                        </div>

                        @if($order->payment_method !== 'cod' && ($order->payment_sender || $order->transaction_id))
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-2">
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase">Sender Reference</span>
                                    <span class="font-bold text-slate-700">{{ $order->payment_sender ?: 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase">Transaction ID</span>
                                    <span class="font-bold text-slate-700 font-mono">{{ $order->transaction_id ?: 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
