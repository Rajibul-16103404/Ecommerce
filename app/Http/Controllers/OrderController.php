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
        if (Auth::check()) {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionCart = session('cart', []);
            $productIds = array_keys($sessionCart);
            if (!empty($productIds)) {
                $products = Product::with('category')->whereIn('id', $productIds)->get();
                $cartItems = $products->map(function ($product) use ($sessionCart) {
                    $item = new CartItem();
                    $item->product_id = $product->id;
                    $item->quantity = $sessionCart[$product->id] ?? 1;
                    $item->setRelation('product', $product);
                    return $item;
                });
            } else {
                $cartItems = collect();
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add products before checking out.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->discounted_price * $item->quantity;
        }

        $shippingLocations = \App\Models\ShippingLocation::all();
        $defaultLocation = $shippingLocations->first();
        $shipping = $subtotal == 0 ? 0.00 : ($defaultLocation ? $defaultLocation->fee : 60.00);
        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'shippingLocations'));
    }

    public function store(Request $request)
    {
        $rules = [
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket,bank_transfer',
            'payment_sender' => 'required_if:payment_method,bkash,nagad,rocket,bank_transfer|nullable|string|max:50',
            'transaction_id' => 'required_if:payment_method,bkash,nagad,rocket,bank_transfer|nullable|string|max:100',
        ];

        if (!Auth::check()) {
            $rules['name'] = 'required|string|max:255';
            if ($request->boolean('create_account')) {
                $rules['email'] = 'required|string|email|max:255|unique:users,email';
                $rules['password'] = 'required|string|min:8|confirmed';
            } else {
                $rules['email'] = 'required|string|email|max:255';
            }
        }

        $request->validate($rules);

        if (Auth::check()) {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionCart = session('cart', []);
            $productIds = array_keys($sessionCart);
            if (!empty($productIds)) {
                $products = Product::with('category')->whereIn('id', $productIds)->get();
                $cartItems = $products->map(function ($product) use ($sessionCart) {
                    $item = new CartItem();
                    $item->product_id = $product->id;
                    $item->quantity = $sessionCart[$product->id] ?? 1;
                    $item->setRelation('product', $product);
                    return $item;
                });
            } else {
                $cartItems = collect();
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->discounted_price * $item->quantity;
        }

        // Retrieve shipping fee based on selected city
        $shippingLocation = \App\Models\ShippingLocation::where('name', $request->shipping_city)->first();
        $shipping = $subtotal == 0 ? 0.00 : ($shippingLocation ? $shippingLocation->fee : 60.00);
        $total = $subtotal + $shipping;

        try {
            $order = DB::transaction(function () use ($request, $cartItems, $total) {
                // Determine user ID
                if (Auth::check()) {
                    $userId = Auth::id();
                } else {
                    if ($request->boolean('create_account')) {
                        $user = \App\Models\User::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => bcrypt($request->password),
                            'role' => 'customer',
                            'phone' => $request->shipping_phone,
                            'address' => $request->shipping_address,
                        ]);
                        Auth::login($user);
                        $userId = $user->id;
                    } else {
                        $user = \App\Models\User::where('email', $request->email)->first();
                        if (!$user) {
                            $user = \App\Models\User::create([
                                'name' => $request->name,
                                'email' => $request->email,
                                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                                'role' => 'customer',
                                'phone' => $request->shipping_phone,
                                'address' => $request->shipping_address,
                            ]);
                        }
                        $userId = $user->id;
                    }
                }

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
                    'user_id' => $userId,
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

                // Clear cart
                if (Auth::check()) {
                    CartItem::where('user_id', Auth::id())->delete();
                } else {
                    session()->forget('cart');
                }

                return $order;
            });

            // Store viewable token for guest orders
            session()->put('viewable_order_'.$order->id, true);

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: '.$e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        // Authorize user
        $canView = false;
        if (Auth::check()) {
            if ($order->user_id === Auth::id() || Auth::user()->isAdmin()) {
                $canView = true;
            }
        }
        if (session()->has('viewable_order_'.$order->id)) {
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'Unauthorized access.');
        }

        $order->load('orderItems.product.category');

        return view('orders.show', compact('order'));
    }
}
