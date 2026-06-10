<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total_price');
        $ordersCount = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $productsCount = Product::count();
        $customersCount = User::where('role', 'customer')->count();
        $vendorsCount = User::where('role', 'vendor')->count();

        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'ordersCount',
            'pendingOrders',
            'productsCount',
            'customersCount',
            'vendorsCount',
            'lowStockProducts',
            'recentOrders'
        ));
    }

    public function productsIndex()
    {
        $products = Product::with('category', 'vendor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function productsCreate()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imageUrl = Storage::url($imagePath);
        }

        Product::create([
            'category_id' => $request->category_id,
            'vendor_id' => Auth::id(), // Assign to current logged in admin
            'name' => $request->name,
            'slug' => str()->slug($request->name).'-'.rand(100, 999),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imageUrl,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function productsEdit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function productsUpdate(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        if ($request->name !== $product->name) {
            $data['slug'] = str()->slug($request->name).'-'.rand(100, 999);
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists and stored locally
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function productsDestroy(Product $product)
    {
        if ($product->image && str_starts_with($product->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function categoriesIndex()
    {
        $categories = Category::withCount('products')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => str()->slug($request->name),
            'description' => $request->description,
            'image' => $request->image ?: 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function categoriesDestroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function ordersIndex()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function ordersShow(Order $order)
    {
        $order->load('orderItems.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function ordersUpdateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to '.ucfirst($request->status).'.');
    }

    public function ordersUpdatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|string|in:pending,pending_verification,paid,failed',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Payment status updated to '.str_replace('_', ' ', ucfirst($request->payment_status)).'.');
    }

    public function vendorsIndex()
    {
        $vendors = User::with('vendorProfile')
            ->where('role', 'vendor')
            ->get();

        return view('admin.vendors.index', compact('vendors'));
    }

    public function vendorsToggleVerify(User $vendor)
    {
        $profile = $vendor->vendorProfile;
        if ($profile) {
            $profile->update([
                'is_verified' => ! $profile->is_verified,
            ]);
        }

        return back()->with('success', 'Vendor verification status toggled.');
    }
}
