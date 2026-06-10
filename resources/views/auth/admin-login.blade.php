@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-gradient-to-br from-emerald-50 via-slate-50 to-orange-50/70 flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-3 bg-white rounded-3xl shadow-xl shadow-emerald-500/5 mb-4 border border-slate-100/80">
                <img src="{{ asset('images/logo.png') }}" alt="Shopee Logo" class="w-16 h-16 rounded-2xl object-cover">
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Shopee <span class="bg-gradient-to-r from-emerald-600 to-orange-500 bg-clip-text text-transparent">Admin Portal</span></h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Secure administrative control room access</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10 space-y-6">
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold uppercase text-slate-400 mb-2">Admin Email Address</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200/60 rounded-2xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm focus:outline-none placeholder-slate-350 text-slate-800 transition duration-250"
                            placeholder="admin@shopee.com">
                    </div>
                    @error('email') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase text-slate-400 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200/60 rounded-2xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm focus:outline-none placeholder-slate-350 text-slate-800 transition duration-250"
                            placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-550 font-medium cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mr-2 w-4 h-4">
                        Remember session
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition duration-300 transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                    <i class="fas fa-shield-alt mr-2 text-xs opacity-90"></i> Authenticate & Enter
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-emerald-600 text-xs font-bold transition flex items-center justify-center gap-1.5">
                <i class="fas fa-arrow-left text-[10px]"></i> Back to Main Storefront
            </a>
        </div>
    </div>
</div>
@endsection
