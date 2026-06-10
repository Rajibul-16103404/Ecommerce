@extends('layouts.admin')

@section('title', 'Manage Vendors - Shopee')
@section('page_title', 'Verify Vendors')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Store Vendors</h1>
            <p class="text-xs text-slate-400 mt-1">Review shop profile credentials, set commissions, and toggle verified checkmarks.</p>
        </div>

        <div class="bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 overflow-hidden shadow-sm">
            @if($vendors->isEmpty())
                <p class="text-slate-400 text-sm text-center py-8">No vendor profiles registered in the system yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400">
                                <th class="pb-3">Vendor / Email</th>
                                <th class="pb-3">Shop Name</th>
                                <th class="pb-3">Shop Description</th>
                                <th class="pb-3 text-center">Commission Rate</th>
                                <th class="pb-3 text-center">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($vendors as $vendor)
                                @php
                                    $profile = $vendor->vendorProfile;
                                @endphp
                                <tr>
                                    <!-- Vendor details -->
                                    <td class="py-4">
                                        <p class="font-bold text-slate-800 text-sm">{{ $vendor->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $vendor->email }}</p>
                                    </td>
                                    
                                    <!-- Shop Name -->
                                    <td class="py-4 font-bold text-slate-800">
                                        {{ $profile ? $profile->shop_name : 'No Shop Registered' }}
                                    </td>
                                    
                                    <!-- Description -->
                                    <td class="py-4 max-w-xs truncate text-slate-500">
                                        {{ $profile ? $profile->description : 'N/A' }}
                                    </td>
                                    
                                    <!-- Commission Rate -->
                                    <td class="py-4 text-center font-bold text-slate-700">
                                        {{ $profile ? $profile->commission_rate . '%' : 'N/A' }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="py-4 text-center">
                                        @if($profile && $profile->is_verified)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-200/50">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                                Unverified
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Action Toggle -->
                                    <td class="py-4 text-right">
                                        @if($profile)
                                            <form method="POST" action="{{ route('admin.vendors.toggle-verify', $vendor->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" 
                                                    class="px-3 py-1.5 rounded-lg font-bold text-[10px] border transition shadow-sm {{ $profile->is_verified ? 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300' : 'bg-emerald-600 border-emerald-600 text-white hover:bg-emerald-700 hover:border-emerald-700' }}">
                                                    {{ $profile->is_verified ? 'Unverify' : 'Verify Shop' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-300 font-semibold italic">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($vendors->hasPages())
                    <div class="pt-4 border-t border-slate-100 mt-4">
                        {{ $vendors->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
