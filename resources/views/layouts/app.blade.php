<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shopee - Premium E-Commerce')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom premium animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .hover-float {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-float:hover {
            animation: float 2s ease-in-out infinite;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.01); }
        }
        .pulse-soft {
            animation: pulse-soft 3s ease-in-out infinite;
        }
        /* Fade in on page load */
        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    @php
        if (auth()->check()) {
            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
            $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
            $sidebarCartItems = \App\Models\CartItem::with('product.category')->where('user_id', auth()->id())->get();
        } else {
            $sessionCart = session('cart', []);
            $cartCount = array_sum($sessionCart);
            $wishlistCount = 0;
            $productIds = array_keys($sessionCart);
            if (!empty($productIds)) {
                $products = \App\Models\Product::with('category')->whereIn('id', $productIds)->get();
                $sidebarCartItems = $products->map(function ($product) use ($sessionCart) {
                    $item = new \App\Models\CartItem();
                    $item->product_id = $product->id;
                    $item->quantity = $sessionCart[$product->id] ?? 1;
                    $item->setRelation('product', $product);
                    return $item;
                });
            } else {
                $sidebarCartItems = collect();
            }
        }
        $sidebarSubtotal = $sidebarCartItems->sum(fn($item) => $item->product->discounted_price * $item->quantity);
    @endphp

    <div x-data="{ cartOpen: false, mobileMenu: false }" class="flex flex-col min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    
                    <!-- Logo & Brand -->
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <img src="{{ asset('images/logo.png') }}" alt="Shopee Logo" class="w-12 h-12 rounded-2xl shadow-lg shadow-emerald-900/10 transform group-hover:rotate-12 transition duration-300 object-cover">
                        <span class="ml-3 text-2xl font-extrabold bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 bg-clip-text text-transparent tracking-tight">
                            Shopee
                        </span>
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="font-semibold text-slate-650 hover:text-emerald-600 transition flex items-center gap-1.5 group {{ request()->routeIs('home') ? 'text-emerald-600' : '' }}">
                            <i class="fas fa-home text-sm transition {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-600 group-hover:scale-110' }}"></i> Home
                        </a>
                        <a href="{{ route('categories.index') }}" class="font-semibold text-slate-650 hover:text-emerald-600 transition flex items-center gap-1.5 group {{ request()->routeIs('categories.*') ? 'text-emerald-600' : '' }}">
                            <i class="fas fa-th-large text-sm transition {{ request()->routeIs('categories.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-600 group-hover:scale-110' }}"></i> Categories
                        </a>
                        <a href="{{ route('products.index') }}" class="font-semibold text-slate-650 hover:text-emerald-600 transition flex items-center gap-1.5 group {{ request()->routeIs('products.*') ? 'text-emerald-600' : '' }}">
                            <i class="fas fa-shopping-bag text-sm transition {{ request()->routeIs('products.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-600 group-hover:scale-110' }}"></i> Products
                        </a>
                    </div>

                    <!-- Actions Panel -->
                    <div class="flex items-center space-x-6">
                        <!-- Search Form (Desktop trigger) -->
                        <div class="relative hidden lg:block w-64">
                            <form action="{{ route('products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2 bg-slate-100/85 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition text-sm focus:outline-none">
                                <button type="submit" class="absolute left-3 top-2.5 text-slate-400 hover:text-emerald-600">
                                    <i class="fas fa-search text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="relative p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-orange-500 transition">
                            <i class="far fa-heart text-lg"></i>
                            @if($wishlistCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-orange-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center ring-2 ring-white">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Shopping Cart Toggle -->
                        <button @click="cartOpen = true" class="relative p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-emerald-650 transition focus:outline-none">
                            <i class="fas fa-shopping-cart text-lg"></i>
                            @if($cartCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-emerald-650 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center ring-2 ring-white">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </button>

                        <!-- User Profile Dropdown / Admin Link / Login -->
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none p-1 rounded-full hover:bg-slate-100 transition">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-orange-500 text-white flex items-center justify-center font-bold text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition">
                                            <i class="fas fa-tachometer-alt mr-3 text-slate-400"></i> Admin Panel
                                        </a>
                                    @endif

                                    <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition">
                                        <i class="fas fa-box mr-3 text-slate-400"></i> My Orders
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition">
                                        <i class="fas fa-heart mr-3 text-slate-400"></i> Wishlist
                                    </a>
                                    <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition">
                                        <i class="fas fa-shopping-cart mr-3 text-slate-400"></i> Shopping Cart
                                    </a>
                                    <hr class="border-slate-100 my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition">
                                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-750 text-white font-bold rounded-xl text-sm hover:shadow-lg hover:shadow-emerald-250 transition transform hover:-translate-y-0.5">
                                Login
                            </a>
                        @endauth
                        
                        <!-- Mobile Menu button -->
                        <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenu" x-transition class="md:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-3 shadow-inner">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition font-medium">
                    <i class="fas fa-home text-slate-400"></i> Home
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition font-medium">
                    <i class="fas fa-th-large text-slate-400"></i> Categories
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition font-medium">
                    <i class="fas fa-shopping-bag text-slate-400"></i> Products
                </a>
                <div class="pt-3 border-t border-slate-100">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 bg-slate-100 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 mt-20 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Shopee Logo" class="w-10 h-10 rounded-xl shadow-lg shadow-emerald-950 object-cover">
                            <span class="ml-3 text-xl font-bold text-white">Shopee</span>
                        </div>
                        <p class="text-sm text-slate-400">Your ultimate destination for curated premium electronics, groceries, fashion, cosmetics, and home essentials. Experience simplicity in shopping.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Categories</h4>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><a href="{{ route('categories.show', 'electronics') }}" class="hover:text-emerald-400 transition">Electronics</a></li>
                            <li><a href="{{ route('categories.show', 'grocery') }}" class="hover:text-emerald-400 transition">Grocery</a></li>
                            <li><a href="{{ route('categories.show', 'makeup') }}" class="hover:text-emerald-400 transition">Beauty & Makeup</a></li>
                            <li><a href="{{ route('categories.show', 'fashion') }}" class="hover:text-emerald-400 transition">Fashion Wear</a></li>
                            <li><a href="{{ route('categories.show', 'home') }}" class="hover:text-emerald-400 transition">Home Essentials</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Customer Support</h4>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition">Shipping Policy</a></li>
                            <li><a href="#" class="hover:text-white transition">Returns & Refunds</a></li>
                            <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white transition">Terms & Conditions</a></li>
                            <li><a href="#" class="hover:text-white transition">Secure Checkout</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Stay Connected</h4>
                        <p class="text-sm text-slate-400 mb-4">Follow us on social media for exclusive updates and product releases.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-emerald-650 hover:text-white flex items-center justify-center transition"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-emerald-650 hover:text-white flex items-center justify-center transition"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-emerald-650 hover:text-white flex items-center justify-center transition"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-emerald-650 hover:text-white flex items-center justify-center transition"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-800 mt-16 pt-8 text-center text-slate-500 text-xs">
                    <p>&copy; 2026 Shopee. All rights reserved. Payment processing via manual gateways.</p>
                </div>
            </div>
        </footer>

        <!-- Slide-over Shopping Cart Sidebar -->
        <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            <div x-show="cartOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="cartOpen = false"></div>
            
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="cartOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-100">
                        <div class="flex-1 overflow-y-auto px-6 py-6 sm:px-6">
                            <div class="flex items-start justify-between border-b border-slate-100 pb-5">
                                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                                    <i class="fas fa-shopping-bag mr-2.5 text-emerald-600"></i> Shopping Cart
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" class="relative -m-2 p-2 text-slate-400 hover:text-slate-500 focus:outline-none" @click="cartOpen = false">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8">
                                <div class="flow-root">
                                    @if($sidebarCartItems->isEmpty())
                                        <div class="text-center py-20">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-shopping-cart text-slate-300 text-xl"></i>
                                            </div>
                                            <p class="text-slate-400 font-medium">Your cart is empty</p>
                                            <a href="{{ route('products.index') }}" @click="cartOpen = false" class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm mt-3 inline-block">Continue Shopping</a>
                                        </div>
                                    @else
                                        <ul role="list" class="-my-6 divide-y divide-slate-100">
                                            @foreach($sidebarCartItems as $item)
                                                <li class="flex py-6">
                                                    <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center">
                                                        @if($item->product->image)
                                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover object-center">
                                                        @else
                                                            <i class="fas fa-image text-slate-300 text-2xl"></i>
                                                        @endif
                                                    </div>

                                                    <div class="ml-4 flex flex-1 flex-col">
                                                        <div>
                                                            <div class="flex justify-between text-sm font-semibold text-slate-900">
                                                                <h3 class="line-clamp-1">
                                                                    <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                                </h3>
                                                                <p class="ml-4">৳{{ number_format($item->product->discounted_price, 2) }}</p>
                                                            </div>
                                                            <p class="mt-1 text-xs text-slate-400">{{ $item->product->category->name }}</p>
                                                        </div>
                                                        <div class="flex flex-1 items-end justify-between text-xs">
                                                            <div class="flex items-center text-slate-500">
                                                                <span class="mr-2">Qty:</span>
                                                                <form method="POST" action="{{ route('cart.update', $item->product->id) }}" class="flex items-center border border-slate-200 bg-white rounded-lg overflow-hidden">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="px-2 py-0.5 hover:bg-slate-100 text-slate-600">−</button>
                                                                    <span class="px-2 font-bold text-slate-800">{{ $item->quantity }}</span>
                                                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-2 py-0.5 hover:bg-slate-100 text-slate-600">+</button>
                                                                </form>
                                                            </div>

                                                            <div class="flex">
                                                                <form method="POST" action="{{ route('cart.remove', $item->product->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="font-semibold text-rose-500 hover:text-rose-600">Remove</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!$sidebarCartItems->isEmpty())
                            <div class="border-t border-slate-100 px-6 py-6 sm:px-6 bg-slate-50">
                                <div class="flex justify-between text-base font-bold text-slate-900">
                                    <p>Subtotal</p>
                                    <p>৳{{ number_format($sidebarSubtotal, 2) }}</p>
                                </div>
                                <p class="mt-1.5 text-xs text-slate-400">Shipping and taxes calculated at checkout.</p>
                                <div class="mt-6 space-y-3">
                                    <a href="{{ route('checkout') }}" class="flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg hover:shadow-emerald-150 transition transform hover:-translate-y-0.5">
                                        Checkout Now
                                    </a>
                                    <a href="{{ route('cart.index') }}" @click="cartOpen = false" class="flex items-center justify-center rounded-xl bg-white border border-slate-200 px-6 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                                        View Shopping Cart
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Alert / Notification Center -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.showToast('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.showToast('{{ session('error') }}', 'error');
                @endif
                @if(session('info'))
                    this.showToast('{{ session('info') }}', 'info');
                @endif
            },
            showToast(msg, type) {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => { this.show = false }, 5000);
            }
         }"
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white border rounded-2xl shadow-xl flex items-center p-4 border-slate-100 overflow-hidden"
         style="display: none;">
        
        <div class="flex-shrink-0 mr-3">
            <template x-if="type === 'success'">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </template>
            <template x-if="type === 'error'">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                </div>
            </template>
            <template x-if="type === 'info'">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
            </template>
        </div>
        
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-800" x-text="type === 'success' ? 'Success' : (type === 'error' ? 'Error' : 'Notification')"></p>
            <p class="text-xs text-slate-500 mt-0.5" x-text="message"></p>
        </div>
        
        <button @click="show = false" class="ml-4 text-slate-400 hover:text-slate-500 focus:outline-none">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

</body>
</html>
