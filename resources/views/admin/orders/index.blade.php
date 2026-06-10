@extends('layouts.admin')

@section('title', 'Manage Orders - Shopee')
@section('page_title', 'Verify Orders')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Purchase Orders</h1>
            <p class="text-xs text-slate-400 mt-1">Review manual transactions, verify TxnIDs, approve payments, and update shipping details.</p>
        </div>

        <div class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 overflow-hidden shadow-sm">
            @if($orders->isEmpty())
                <p class="text-slate-400 text-sm text-center py-8">No orders registered in the system yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400">
                                <th class="pb-3">Order Number</th>
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Date Placed</th>
                                <th class="pb-3">Payment Method</th>
                                <th class="pb-3">Payment Status</th>
                                <th class="pb-3">Order Status</th>
                                <th class="pb-3 text-right">Grand Total</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($orders as $order)
                                <tr>
                                    <!-- Order Number -->
                                    <td class="py-4 font-bold text-slate-800">{{ $order->order_number }}</td>
                                    
                                    <!-- Customer name/email -->
                                    <td class="py-4">
                                        <p class="font-bold text-slate-800">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $order->user->email }}</p>
                                    </td>
                                    
                                    <!-- Date -->
                                    <td class="py-4 text-slate-400">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    
                                    <!-- Payment Method -->
                                    <td class="py-4 font-semibold uppercase text-[10px] text-slate-500">
                                        {{ str_replace('_', ' ', $order->payment_method) }}
                                    </td>
                                    
                                    <!-- Payment status badge -->
                                    <td class="py-4">
                                        @if($order->payment_status === 'paid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-50 text-green-600 border border-green-200/50 font-bold">Paid</span>
                                        @elseif($order->payment_status === 'pending_verification')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-orange-50 text-orange-600 border border-orange-200/50 font-bold">Verify Txn</span>
                                        @elseif($order->payment_status === 'failed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-50 text-red-600 border border-red-200/50 font-bold">Failed</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-bold">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Order status badge -->
                                    <td class="py-4">
                                        @if($order->status === 'delivered')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-50 text-green-600 border border-green-200/50 font-bold">Delivered</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-50 text-purple-600 border border-purple-200/50 font-bold">Shipped</span>
                                        @elseif($order->status === 'processing')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-600 border border-blue-200/50 font-bold">Processing</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-50 text-red-600 border border-red-200/50 font-bold">Cancelled</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-bold">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Grand Total -->
                                    <td class="py-4 text-right font-bold text-slate-800">৳{{ number_format($order->total_price, 2) }}</td>
                                    
                                    <!-- Actions -->
                                    <td class="py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 bg-white border border-slate-200 text-emerald-600 hover:border-emerald-500 hover:text-emerald-705 rounded-lg font-bold transition shadow-sm">
                                            Review details
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
            @endif
        </div>

    </div>
@endsection
