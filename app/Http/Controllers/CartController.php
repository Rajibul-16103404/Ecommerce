<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShippingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = CartItem::with('product.category')
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

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->discounted_price * $item->quantity;
        }

        $defaultLocation = ShippingLocation::first();
        $defaultShippingFee = $defaultLocation ? $defaultLocation->fee : 60.00;
        $shipping = $subtotal == 0 ? 0.00 : $defaultShippingFee;
        $total = $subtotal + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->get('quantity', 1);

        if ($product->stock < $quantity) {
            return back()->with('error', 'Sorry, only '.$product->stock.' units of this product are in stock.');
        }

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($product->stock < $newQuantity) {
                    return back()->with('error', 'Cannot add more. Total in cart ('.$newQuantity.') exceeds available stock.');
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            $currentQuantity = $cart[$product->id] ?? 0;
            $newQuantity = $currentQuantity + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Cannot add more. Total in cart ('.$newQuantity.') exceeds available stock.');
            }
            $cart[$product->id] = $newQuantity;
            session()->put('cart', $cart);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', $product->name.' has been added to your cart.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = (int) $request->quantity;

        if ($product->stock < $quantity) {
            return back()->with('error', 'Only '.$product->stock.' units are available in stock.');
        }

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->firstOrFail();

            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cart = session()->get('cart', []);
            $cart[$product->id] = $quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Product $product)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', $product->name.' removed from cart.');
    }
}
