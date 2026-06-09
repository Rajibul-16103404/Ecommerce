<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewService
{
    public function createReview(Product $product, User $user, int $rating, ?string $comment = null): Review
    {
        return Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => max(1, min(5, $rating)), // Ensure rating is between 1-5
            'comment' => $comment,
        ]);
    }

    public function updateReview(Review $review, int $rating, ?string $comment = null): Review
    {
        $review->update([
            'rating' => max(1, min(5, $rating)),
            'comment' => $comment,
        ]);

        return $review;
    }

    public function deleteReview(Review $review): bool
    {
        return $review->delete();
    }

    public function getProductReviews(Product $product, int $perPage = 10)
    {
        return $product->reviews()
            ->with('user')
            ->orderBy('helpful_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function markHelpful(Review $review): Review
    {
        $review->increment('helpful_count');

        return $review;
    }

    public function getAverageRating(Product $product): float
    {
        return $product->reviews()->avg('rating') ?? 0;
    }

    public function getRatingDistribution(Product $product): array
    {
        $total = $product->reviews()->count();

        if ($total === 0) {
            return [
                '5' => 0,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0,
            ];
        }

        return [
            '5' => round(($product->reviews()->where('rating', 5)->count() / $total) * 100),
            '4' => round(($product->reviews()->where('rating', 4)->count() / $total) * 100),
            '3' => round(($product->reviews()->where('rating', 3)->count() / $total) * 100),
            '2' => round(($product->reviews()->where('rating', 2)->count() / $total) * 100),
            '1' => round(($product->reviews()->where('rating', 1)->count() / $total) * 100),
        ];
    }
}
