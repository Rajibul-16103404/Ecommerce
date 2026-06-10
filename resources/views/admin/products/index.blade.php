@extends('layouts.admin')

@section('title', 'Manage Products - ShopHub')
@section('page_title', 'Products CRUD')

@section('content')
    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">Active Inventory</h1>
                <p class="text-xs text-slate-500 mt-1">Review, search, edit, or delete items from the store.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-750 text-white font-bold text-xs rounded-xl hover:shadow-lg transition">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
        </div>

        <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 overflow-hidden shadow-sm">
            @if($products->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 text-sm">No products found in the database.</p>
                    <a href="{{ route('admin.products.create') }}" class="mt-4 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg inline-block">Create First Product</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs text-slate-400">
                        <thead>
                            <tr class="border-b border-slate-850 text-[10px] font-bold uppercase text-slate-500">
                                <th class="pb-3">Image</th>
                                <th class="pb-3">Product Name</th>
                                <th class="pb-3">Category</th>
                                <th class="pb-3 text-center">Stock Level</th>
                                <th class="pb-3">Base Price</th>
                                <th class="pb-3">Discount Price</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            @foreach($products as $product)
                                <tr>
                                    <!-- Image -->
                                    <td class="py-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-image text-slate-650 text-base"></i>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Title/Slug -->
                                    <td class="py-4 font-bold text-slate-200">
                                        <p class="text-sm font-bold text-white">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $product->slug }}</p>
                                    </td>
                                    
                                    <!-- Category -->
                                    <td class="py-4 text-xs font-semibold uppercase text-indigo-400">
                                        {{ $product->category->name }}
                                    </td>
                                    
                                    <!-- Stock -->
                                    <td class="py-4 text-center">
                                        @if($product->stock > 5)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-500/10 text-green-400 border border-green-500/20 font-bold">{{ $product->stock }} left</span>
                                        @elseif($product->stock > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20 font-bold">{{ $product->stock }} left</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-500/10 text-rose-450 border border-rose-500/20 font-bold">Out of Stock</span>
                                        @endif
                                    </td>

                                    <!-- Price -->
                                    <td class="py-4 font-bold text-slate-300">${{ number_format($product->price, 2) }}</td>
                                    
                                    <!-- Discount Price -->
                                    <td class="py-4">
                                        @if($product->discount_price)
                                            <span class="font-bold text-slate-200">${{ number_format($product->discount_price, 2) }}</span>
                                        @else
                                            <span class="text-slate-600 font-semibold italic">N/A</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Actions CRUD -->
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="px-2.5 py-1 bg-slate-900 border border-slate-800 text-indigo-400 hover:border-indigo-500 rounded-lg font-bold transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-slate-900 border border-slate-800 text-rose-400 hover:border-rose-500 hover:bg-rose-950/20 rounded-lg font-bold transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pt-4 border-t border-slate-850">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
