@extends('layouts.app')

@section('title', 'My Purchase Orders - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8">Order History</h1>

        @if($orders->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-box-open text-slate-350 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">No Orders Placed Yet</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto">You haven't made any purchases yet. Start browsing products to place your first order.</p>
                <a href="{{ route('products.index') }}" class="mt-6 px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-2xl text-sm transition inline-block">
                    Explore Shop
                </a>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-slate-600 text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="py-4 font-bold">Order Number</th>
                                <th class="py-4 font-bold">Date Placed</th>
                                <th class="py-4 font-bold">Shipping Info</th>
                                <th class="py-4 font-bold">Payment Status</th>
                                <th class="py-4 font-bold">Order Status</th>
                                <th class="py-4 font-bold text-right">Grand Total</th>
                                <th class="py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($orders as $order)
                                <tr>
                                    <!-- Order number -->
                                    <td class="py-5 font-bold text-slate-800">{{ $order->order_number }}</td>
                                    
                                    <!-- Date -->
                                    <td class="py-5 text-slate-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    
                                    <!-- Shipping info -->
                                    <td class="py-5 text-xs">
                                        <p class="font-medium text-slate-800">{{ $order->shipping_city }}</p>
                                        <p class="text-slate-400">{{ $order->shipping_phone }}</p>
                                    </td>
                                    
                                    <!-- Payment Status Badge -->
                                    <td class="py-5">
                                        @if($order->payment_status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700">Paid</span>
                                        @elseif($order->payment_status === 'pending_verification')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-50 text-orange-700">Verifying</span>
                                        @elseif($order->payment_status === 'failed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700">Failed</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Pending</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Order status badge -->
                                    <td class="py-5">
                                        @if($order->status === 'delivered')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700">Delivered</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700">Shipped</span>
                                        @elseif($order->status === 'processing')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700">Processing</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700">Cancelled</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Pending</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Total -->
                                    <td class="py-5 text-right font-bold text-slate-900">৳{{ number_format($order->total_price, 2) }}</td>
                                    
                                    <!-- Action -->
                                    <td class="py-5 text-right">
                                        <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs font-bold rounded-lg transition inline-block">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pt-4 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
