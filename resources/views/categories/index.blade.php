@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Page Title -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 tracking-wider uppercase">
            Categories Directory
        </span>
        <h1 class="text-4xl font-extrabold text-slate-900">Browse Our Categories</h1>
        <p class="text-slate-500">Explore all our curated products organized by departments</p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($categories as $category)
            @php
                $slug = $category->slug;
                $icon = match($slug) {
                    'electronics' => 'fa-laptop-code',
                    'grocery' => 'fa-apple-whole',
                    'makeup' => 'fa-wand-magic-sparkles',
                    'fashion' => 'fa-shirt',
                    'home' => 'fa-couch',
                    default => 'fa-tags'
                };
                $gradient = match($slug) {
                    'electronics' => 'from-emerald-500 to-emerald-600',
                    'grocery' => 'from-orange-500 to-orange-600',
                    'makeup' => 'from-emerald-600 to-teal-700',
                    'fashion' => 'from-orange-600 to-amber-600',
                    'home' => 'from-emerald-500 to-orange-550',
                    default => 'from-emerald-600 to-orange-500'
                };
            @endphp
            <a href="{{ route('categories.show', $category->slug) }}" class="group">
                <div class="bg-gradient-to-br {{ $gradient }} rounded-3xl p-8 hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 h-full relative overflow-hidden flex flex-col justify-between text-white border border-white/10 shadow-lg">
                    <div class="absolute -right-8 -bottom-8 w-28 h-28 bg-white/10 rounded-full pointer-events-none group-hover:scale-150 transition duration-300"></div>
                    <div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition duration-300">
                            <i class="fas {{ $icon }} text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black mb-2">{{ $category->name }}</h3>
                        <p class="text-white/80 text-sm leading-relaxed mb-6">{{ $category->description }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-white/10">
                        <span class="text-xs font-bold uppercase tracking-wider">{{ $category->products_count }} Products Available</span>
                        <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition duration-200"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-20 bg-white border border-slate-100 rounded-3xl">
                <i class="fas fa-folder-open text-slate-300 text-5xl mb-4"></i>
                <p class="text-slate-400 font-bold">No categories found in database</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
