@extends('layouts.admin')

@section('title', 'Add Product - ShopHub')
@section('page_title', 'Create Product')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-indigo-400 hover:underline flex items-center mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Inventory
                </a>
                <h1 class="text-2xl font-black text-white tracking-tight">Add New Product</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold uppercase text-slate-500 mb-2">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100 placeholder-slate-600"
                        placeholder="e.g. Wireless Noise-Cancelling Headphones">
                    @error('name') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase text-slate-500 mb-2">Category</label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-xs font-bold uppercase text-slate-500 mb-2">Stock Level</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100">
                    @error('stock') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label for="price" class="block text-xs font-bold uppercase text-slate-500 mb-2">Price ($)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100"
                        placeholder="e.g. 99.99">
                    @error('price') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Discount Price -->
                <div>
                    <label for="discount_price" class="block text-xs font-bold uppercase text-slate-500 mb-2">Discount Price ($) <span class="text-[9px] text-slate-500 lowercase">(optional)</span></label>
                    <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100"
                        placeholder="e.g. 79.99">
                    @error('discount_price') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold uppercase text-slate-500 mb-2">Product Description</label>
                    <textarea id="description" name="description" rows="5" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-100 placeholder-slate-600"
                        placeholder="Provide details about specs, features, size guide, warranty, etc.">{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Image upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block text-xs font-bold uppercase text-slate-500 mb-2">Product Image File</label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm focus:outline-none text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700">
                    <span class="text-[10px] text-slate-500 mt-2 block">Upload high resolution square image for listing cards. PNG, JPG, or WEBP. Max 2MB.</span>
                    @error('image') <span class="text-xs text-rose-450 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-3 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-400 hover:text-slate-200 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-650 hover:bg-indigo-750 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-indigo-500/10">
                    Save Product
                </button>
            </div>
        </form>
    </div>
@endsection
