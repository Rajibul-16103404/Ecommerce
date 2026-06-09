@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Page Title -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Browse Categories</h1>
        <p class="text-gray-600">Explore all our product categories</p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" class="group">
            <div class="bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg p-8 hover:shadow-2xl transform hover:scale-105 transition duration-300 h-full cursor-pointer">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-2">{{ $category->name }}</h3>
                    <p class="text-blue-100 mb-4">{{ $category->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold">{{ $category->products_count }} Products</span>
                        <svg class="w-6 h-6 transform group-hover:translate-x-2 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500">No categories found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
