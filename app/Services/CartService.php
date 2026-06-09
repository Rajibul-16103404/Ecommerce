<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function getCart(User $user): Collection
    {
        return $user->cartItems()->with('product')->get();
    }

    public function addToCart(User $user, Product $product, int $quantity = 1): CartItem
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);

            return $cartItem;
        }

        return CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(User $user, Product $product, int $quantity): CartItem
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem;
    }

    public function removeFromCart(User $user, Product $product): void
    {
        CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();
    }

    public function clearCart(User $user): void
    {
        CartItem::where('user_id', $user->id)->delete();
    }

    public function getCartTotal(User $user): float
    {
        return $user->cartItems()
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->getDiscountedPriceAttribute() * $item->quantity;
            });
    }

    public function getCartItemCount(User $user): int
    {
        return $user->cartItems()->sum('quantity');
    }
}
