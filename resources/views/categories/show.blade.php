@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8 flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Home</a>
        <span>/</span>
        <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-700">Categories</a>
        <span>/</span>
        <span class="text-gray-900 font-semibold">{{ $category->name }}</span>
    </nav>

    <!-- Category Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
        <p class="text-gray-600 text-lg">{{ $category->description }}</p>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products->items() as $product)
        <div class="bg-white rounded-lg shadow hover:shadow-xl transition duration-300 overflow-hidden group">
            <!-- Product Image -->
            <div class="relative overflow-hidden bg-gray-100 h-64">
                <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                @if($product->discount_price)
                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $product->discount_percentage }}% OFF
                </div>
                @endif
                <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    {{ $category->name }}
                </div>
            </div>

            <!-- Product Details -->
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $product->description }}</p>

                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < floor($product->average_rating))
                                <span class="text-lg">★</span>
                            @else
                                <span class="text-lg text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600 ml-2">({{ count($product->reviews) }} reviews)</span>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    @if($product->discount_price)
                        <span class="text-2xl font-bold text-blue-600">৳{{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-sm text-gray-400 line-through ml-2">৳{{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-2xl font-bold text-gray-900">৳{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Status -->
                <div class="mb-4">
                    @if($product->stock > 0)
                        <span class="text-sm text-green-600 font-semibold">✓ In Stock</span>
                    @else
                        <span class="text-sm text-red-600 font-semibold">✗ Out of Stock</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('products.show', $product->slug) }}" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-center font-semibold transition">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 text-lg">No products found in this category</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
