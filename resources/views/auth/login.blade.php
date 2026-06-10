@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] bg-gradient-to-br from-emerald-50 to-orange-50/60 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10 space-y-6">
            <div class="text-center">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
                <p class="text-slate-500 text-sm mt-2">Sign in to your Shopee account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold uppercase text-slate-400 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase text-slate-400 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350">
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/10 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                    Sign In
                </button>
            </form>

            <p class="text-center text-slate-500 text-sm pt-4 border-t border-slate-100">
                Don't have an account? <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-bold">Create one</a>
            </p>
        </div>
    </div>
</div>
@endsection
