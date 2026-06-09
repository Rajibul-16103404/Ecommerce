<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopHub - Multi-Category E-Commerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="font-sans antialiased bg-white">
    <div x-data="{ sidebarOpen: false, cartOpen: false }" class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            <i class="fas fa-shopping-bag mr-2"></i>ShopHub
                        </div>
                    </a>

                    <!-- Search Bar -->
                    <div class="flex-1 mx-8 hidden md:block">
                        <form action="{{ route('products.index') }}" method="GET" class="flex">
                            <input type="text" name="search" placeholder="Search products..." 
                                class="flex-1 px-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ request('search', '') }}">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-blue-600 transition relative">
                            <i class="fas fa-heart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                        
                        <button @click="cartOpen = true" class="text-gray-700 hover:text-blue-600 transition relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </button>

                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-user-circle text-2xl"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false" 
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-box mr-2"></i>My Orders
                                    </a>
                                    <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-shopping-cart mr-2"></i>Cart
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Breadcrumb -->
        @if (isset($breadcrumbs))
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <div class="max-w-7xl mx-auto flex items-center space-x-2 text-sm">
                    @foreach ($breadcrumbs as $name => $url)
                        @if ($url)
                            <a href="{{ $url }}" class="text-blue-600 hover:underline">{{ $name }}</a>
                        @else
                            <span class="text-gray-700">{{ $name }}</span>
                        @endif
                        @if (!$loop->last)
                            <span class="text-gray-400">/</span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="grid grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            ShopHub
                        </h3>
                        <p class="text-gray-400">Your one-stop shop for everything you need.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                            <li><a href="{{ route('categories.index') }}" class="hover:text-white transition">Categories</a></li>
                            <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Products</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition">Shipping</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition text-xl"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white transition text-xl"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white transition text-xl"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                    <p>&copy; 2024 ShopHub. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Cart Sidebar -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black opacity-50" @click="cartOpen = false"></div>
        <div class="absolute right-0 top-0 bottom-0 w-96 bg-white shadow-lg overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-xl font-bold">Shopping Cart</h2>
                <button @click="cartOpen = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="text-center text-gray-500 py-8">
                    <p>Your cart is empty</p>
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg animate-slide-in z-50">
            {{ session('success') }}
        </div>
    @endif
</body>
</html>
