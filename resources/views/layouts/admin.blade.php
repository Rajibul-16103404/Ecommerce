<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - ShopHub')</title>
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
    </style>
</head>
<body class="bg-slate-900 text-slate-100 flex min-h-screen">

    <div x-data="{ sidebarOpen: true }" class="flex w-full min-h-screen">
        
        <!-- Sidebar Navigation -->
        <aside class="bg-slate-950 border-r border-slate-800 w-64 flex-shrink-0 transition-all duration-300 flex flex-col justify-between"
            :class="sidebarOpen ? 'block' : 'hidden md:block md:w-20'">
            
            <div class="space-y-8">
                <!-- Branding Header -->
                <div class="h-20 flex items-center px-6 border-b border-slate-800/60 bg-slate-950">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white shadow-lg">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span x-show="sidebarOpen" class="ml-3 text-lg font-extrabold text-white tracking-tight">
                            ShopHub Admin
                        </span>
                    </a>
                </div>

                <!-- Nav Menu links -->
                <nav class="px-4 space-y-2.5">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-500' : '' }}">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span x-show="sidebarOpen">Dashboard</span>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-500' : '' }}">
                        <i class="fas fa-box w-6"></i>
                        <span x-show="sidebarOpen">Products CRUD</span>
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-500' : '' }}">
                        <i class="fas fa-folder w-6"></i>
                        <span x-show="sidebarOpen">Categories</span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400 {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-500' : '' }}">
                        <i class="fas fa-clipboard-list w-6"></i>
                        <span x-show="sidebarOpen">Verify Orders</span>
                    </a>

                    <!-- Vendors -->
                    <a href="{{ route('admin.vendors.index') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400 {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-500' : '' }}">
                        <i class="fas fa-store w-6"></i>
                        <span x-show="sidebarOpen">Verify Vendors</span>
                    </a>

                    <!-- Spacer -->
                    <hr class="border-slate-850 my-6">

                    <!-- Store Globe link -->
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900 hover:text-indigo-400">
                        <i class="fas fa-globe w-6"></i>
                        <span x-show="sidebarOpen">View Website</span>
                    </a>
                </nav>
            </div>

            <!-- Footer User info -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-200 truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 truncate leading-none mt-1">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-rose-950 hover:text-rose-400 text-slate-400 text-xs font-bold rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Layout Area -->
        <div class="flex-1 flex flex-col min-h-screen bg-slate-900 overflow-hidden">
            <!-- Header bar -->
            <header class="bg-slate-950 h-20 flex items-center justify-between px-6 sm:px-8 border-b border-slate-800/60 sticky top-0 z-30 shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-slate-900 border border-slate-800 text-slate-400 hover:text-indigo-400 rounded-lg transition mr-4 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="font-extrabold text-white text-lg tracking-tight">@yield('page_title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-slate-500 text-xs hidden sm:inline">Current System Date: {{ now()->format('M d, Y') }}</span>
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 text-slate-200 text-xs font-bold rounded-lg hover:bg-slate-850 hover:text-white transition flex items-center">
                        <i class="fas fa-globe mr-2"></i> Visit Front Office
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 sm:p-8">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Alert / Notification Toast -->
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
         class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-slate-950 border rounded-2xl shadow-2xl flex items-center p-4 border-slate-800 overflow-hidden text-slate-200"
         style="display: none;">
        
        <div class="flex-shrink-0 mr-3">
            <template x-if="type === 'success'">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center border border-green-500/20">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </template>
            <template x-if="type === 'error'">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center border border-red-500/20">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                </div>
            </template>
        </div>
        
        <div class="flex-1">
            <p class="text-sm font-bold text-white" x-text="type === 'success' ? 'Success' : 'Error'"></p>
            <p class="text-xs text-slate-400 mt-0.5" x-text="message"></p>
        </div>
        
        <button @click="show = false" class="ml-4 text-slate-500 hover:text-slate-400 focus:outline-none">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

</body>
</html>
