<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function search(
        ?string $query = null,
        ?int $categoryId = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $sort = 'latest',
        int $perPage = 12
    ): LengthAwarePaginator {
        $q = Product::query();

        if ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        }

        if ($categoryId) {
            $q->where('category_id', $categoryId);
        }

        if ($minPrice !== null) {
            $q->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $q->where('price', '<=', $maxPrice);
        }

        $q->where('stock', '>', 0);

        match ($sort) {
            'price_low' => $q->orderBy('price', 'asc'),
            'price_high' => $q->orderBy('price', 'desc'),
            'popular' => $q->orderBy('views', 'desc'),
            'trending' => $q->orderByRaw('(views / DATEDIFF(NOW(), created_at) + 1) DESC'),
            default => $q->orderBy('created_at', 'desc'), // latest
        };

        return $q->paginate($perPage);
    }

    public function getProductsByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return Product::where('category_id', $categoryId)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getFeaturedProducts(int $limit = 8): array
    {
        return Product::where('stock', '>', 0)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getTrendingProducts(int $limit = 8): array
    {
        return Product::where('stock', '>', 0)
            ->orderByRaw('(views / DATEDIFF(NOW(), created_at) + 1) DESC')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
