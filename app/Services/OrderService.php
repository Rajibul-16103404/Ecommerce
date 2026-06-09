<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrderFromCart(User $user, string $address, string $city, string $zip, string $phone): Order
    {
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw new \Exception('Cart is empty');
        }

        $total = 0;

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $this->generateOrderNumber(),
            'total_price' => 0, // Will update after creating items
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => $address,
            'shipping_city' => $city,
            'shipping_zip' => $zip,
            'shipping_phone' => $phone,
        ]);

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            $price = $product->getDiscountedPriceAttribute();
            $itemTotal = $price * $cartItem->quantity;
            $total += $itemTotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cartItem->quantity,
                'price' => $price,
            ]);

            // Reduce stock
            $product->decrement('stock', $cartItem->quantity);
        }

        $order->update(['total_price' => $total]);

        // Clear cart
        CartItem::where('user_id', $user->id)->delete();

        return $order;
    }

    public function getOrdersByUser(User $user): Collection
    {
        return $user->orders()->with('orderItems.product')->get();
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order;
    }

    public function updatePaymentStatus(Order $order, string $status): Order
    {
        $order->update(['payment_status' => $status]);

        return $order;
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('YmdHis').'-'.Str::random(6);
    }
}
