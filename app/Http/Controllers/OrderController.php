<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add products before checking out.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->discounted_price * $item->quantity;
        }

        $shipping = $subtotal > 50 ? 0.00 : 9.99;
        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket,bank_transfer',
            'payment_sender' => 'required_if:payment_method,bkash,nagad,rocket,bank_transfer|nullable|string|max:50',
            'transaction_id' => 'required_if:payment_method,bkash,nagad,rocket,bank_transfer|nullable|string|max:100',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->discounted_price * $item->quantity;
        }
        $shipping = $subtotal > 50 ? 0.00 : 9.99;
        $total = $subtotal + $shipping;

        try {
            $order = DB::transaction(function () use ($request, $cartItems, $total) {
                // Verify stock availability
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product->stock < $item->quantity) {
                        throw new \Exception('Insufficient stock for product: '.$product->name);
                    }
                    // Decrement stock
                    $product->decrement('stock', $item->quantity);
                }

                // Create Order
                $orderNumber = 'ORD-'.strtoupper(str()->random(10));

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => $orderNumber,
                    'total_price' => $total,
                    'status' => 'pending',
                    'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending_verification',
                    'shipping_address' => $request->shipping_address,
                    'shipping_city' => $request->shipping_city,
                    'shipping_zip' => $request->shipping_zip,
                    'shipping_phone' => $request->shipping_phone,
                    'payment_method' => $request->payment_method,
                    'payment_sender' => $request->payment_sender,
                    'transaction_id' => $request->transaction_id,
                ]);

                // Create Order Items
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->discounted_price,
                    ]);
                }

                // Clear user cart
                CartItem::where('user_id', Auth::id())->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: '.$e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        // Authorize user
        if ($order->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load('orderItems.product.category');

        return view('orders.show', compact('order'));
    }
}
