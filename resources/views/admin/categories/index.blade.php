@extends('layouts.admin')

@section('title', 'Manage Categories - Shopee')
@section('page_title', 'Categories CRUD')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Product Categories</h1>
            <p class="text-xs text-slate-400 mt-1">Add, update, or remove categories of items listed in the store.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Category list table -->
            <div class="lg:col-span-8 bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-4 mb-6">Active Categories</h3>
                
                @if($categories->isEmpty())
                    <p class="text-slate-400 text-sm text-center py-8">No categories found in the database.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-slate-655">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400">
                                    <th class="pb-3">Name / Slug</th>
                                    <th class="pb-3">Description</th>
                                    <th class="pb-3 text-center">Products Count</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="py-4">
                                            <p class="font-bold text-slate-800 text-sm">{{ $category->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $category->slug }}</p>
                                        </td>
                                        <td class="py-4 max-w-xs truncate text-slate-500">{{ $category->description ?: 'No description provided' }}</td>
                                        <td class="py-4 text-center font-bold text-slate-800">{{ $category->products_count }}</td>
                                        <td class="py-4 text-right">
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Delete this category? ALL products under it will be deleted!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-white border border-slate-200 text-rose-600 hover:border-rose-500 hover:bg-rose-50 rounded-lg font-bold transition shadow-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($categories->hasPages())
                    <div class="pt-4 border-t border-slate-100 mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

            <!-- Create category form -->
            <div class="lg:col-span-4 bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-4 mb-6">Create Category</h3>
                
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase text-slate-400 mb-2">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs focus:outline-none text-slate-800 placeholder-slate-400"
                            placeholder="e.g. Cosmetics">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase text-slate-400 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs focus:outline-none text-slate-800 placeholder-slate-400"
                            placeholder="e.g. Perfumes, foundations, lipsticks and other makeup accessories..."></textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-bold uppercase text-slate-400 mb-2">Banner Image URL <span class="text-[9px] text-slate-400 lowercase">(optional)</span></label>
                        <input type="url" id="image" name="image" value="{{ old('image') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs focus:outline-none text-slate-800 placeholder-slate-400"
                            placeholder="e.g. https://images.unsplash.com/...">
                        @error('image') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-lg shadow-emerald-500/10">
                        Create Category
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
