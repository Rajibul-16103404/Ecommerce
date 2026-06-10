@extends('layouts.admin')

@section('title', 'Edit Product - Shopee')
@section('page_title', 'Edit Product')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-emerald-600 hover:underline flex items-center mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Inventory
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Product: <span class="text-emerald-600">{{ $product->name }}</span></h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold uppercase text-slate-400 mb-2">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800 placeholder-slate-400">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase text-slate-400 mb-2">Category</label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-xs font-bold uppercase text-slate-400 mb-2">Stock Level</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800">
                    @error('stock') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-xs font-bold uppercase text-slate-400 mb-2">Price (৳)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800">
                    @error('price') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Discount Price -->
                <div>
                    <label for="discount_price" class="block text-xs font-bold uppercase text-slate-400 mb-2">Discount Price (৳) <span class="text-[9px] text-slate-400 lowercase">(optional)</span></label>
                    <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800">
                    @error('discount_price') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold uppercase text-slate-400 mb-2">Product Description</label>
                    <textarea id="description" name="description" rows="5" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-800 placeholder-slate-400">{{ old('description', $product->description) }}</textarea>
                    @error('description') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Current Image & Replacement -->
                <div class="sm:col-span-2 space-y-4">
                    <label class="block text-xs font-bold uppercase text-slate-400">Product Image File</label>
                    
                    @if($product->image)
                        <div class="flex items-center space-x-4 p-4 bg-slate-50 border border-slate-150 rounded-2xl w-max">
                            <img src="{{ $product->image }}" alt="Current Product Image" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                            <div>
                                <span class="block text-xs font-bold text-slate-500">Current Image Link</span>
                                <a href="{{ $product->image }}" target="_blank" class="text-[10px] text-emerald-600 hover:underline truncate max-w-xs block font-mono">{{ $product->image }}</a>
                            </div>
                        </div>
                    @endif

                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm focus:outline-none text-slate-550 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-emerald-600 hover:file:bg-slate-300 transition cursor-pointer">
                    <span class="text-[10px] text-slate-400 mt-2 block">Upload a replacement image to overwrite the existing one. PNG, JPG, or WEBP. Max 2MB.</span>
                    @error('image') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Action buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-emerald-500/10">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
