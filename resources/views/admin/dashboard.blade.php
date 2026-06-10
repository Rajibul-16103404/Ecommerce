@extends('layouts.admin')

@section('title', 'Admin Dashboard - ShopHub')
@section('page_title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        
        <!-- Summary Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Sales Card -->
            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 shadow-sm flex items-center space-x-5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Total Sales</span>
                    <span class="text-2xl font-black text-white mt-1 block">${{ number_format($totalSales, 2) }}</span>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 shadow-sm flex items-center space-x-5">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Total Purchases</span>
                    <span class="text-2xl font-black text-white mt-1 block">{{ $ordersCount }} Orders</span>
                </div>
            </div>

            <!-- Products Card -->
            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 shadow-sm flex items-center space-x-5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Active Products</span>
                    <span class="text-2xl font-black text-white mt-1 block">{{ $productsCount }} Items</span>
                </div>
            </div>

            <!-- Pending Orders Card -->
            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 shadow-sm flex items-center space-x-5 animate-pulse">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Approval</span>
                    <span class="text-2xl font-black text-white mt-1 block">{{ $pendingOrders }} Orders</span>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Recent Orders (Left side) -->
            <div class="lg:col-span-8 bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-850">
                    <h3 class="font-extrabold text-white text-base">Recent Orders Summary</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-450 hover:underline">View All Orders</a>
                </div>

                @if($recentOrders->isEmpty())
                    <p class="text-slate-500 text-sm py-8 text-center">No orders registered in the system yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-slate-400">
                            <thead>
                                <tr class="border-b border-slate-850 text-[10px] font-bold uppercase text-slate-500">
                                    <th class="pb-3">Order Number</th>
                                    <th class="pb-3">Customer</th>
                                    <th class="pb-3">Method</th>
                                    <th class="pb-3">Payment</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-right">Total</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-850">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="py-4 font-bold text-slate-200">{{ $order->order_number }}</td>
                                        <td class="py-4 font-medium text-slate-300">{{ $order->user->name }}</td>
                                        <td class="py-4 uppercase">{{ $order->payment_method }}</td>
                                        <td class="py-4">
                                            @if($order->payment_status === 'paid')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Paid</span>
                                            @elseif($order->payment_status === 'pending_verification')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20">Verify</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-800 text-slate-400">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($order->status === 'delivered')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Delivered</span>
                                            @elseif($order->status === 'shipped')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">Shipped</span>
                                            @elseif($order->status === 'processing')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Processing</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-800 text-slate-400">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right font-bold text-slate-200">${{ number_format($order->total_price, 2) }}</td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 bg-slate-900 border border-slate-850 hover:border-indigo-500 text-indigo-400 text-[10px] font-bold rounded-lg transition inline-block">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Stock Alerts Panel (Right side) -->
            <div class="lg:col-span-4 bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="pb-4 border-b border-slate-850">
                    <h3 class="font-extrabold text-white text-base">Inventory Warnings</h3>
                    <p class="text-xs text-slate-500 mt-1">Products with stock level &le; 5 units.</p>
                </div>

                @if($lowStockProducts->isEmpty())
                    <div class="text-center py-10">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-3"></i>
                        <p class="text-slate-400 font-bold text-xs">All Stocks Good</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">No immediate refills required.</p>
                    </div>
                @else
                    <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3.5 bg-slate-900/50 border border-slate-850 rounded-2xl">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-200 text-xs truncate">{{ $product->name }}</h4>
                                    <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-0.5 block">{{ $product->category->name }}</span>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg bg-rose-500/10 text-rose-400 font-black text-xs border border-rose-500/20">
                                    {{ $product->stock }} left
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection
