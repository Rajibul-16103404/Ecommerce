@extends('layouts.admin')

@section('title', 'Manage Vendors - ShopHub')
@section('page_title', 'Verify Vendors')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Store Vendors</h1>
            <p class="text-xs text-slate-500 mt-1">Review shop profile credentials, set commissions, and toggle verified checkmarks.</p>
        </div>

        <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 overflow-hidden shadow-sm">
            @if($vendors->isEmpty())
                <p class="text-slate-500 text-sm text-center py-8">No vendor profiles registered in the system yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs text-slate-400">
                        <thead>
                            <tr class="border-b border-slate-850 text-[10px] font-bold uppercase text-slate-500">
                                <th class="pb-3">Vendor / Email</th>
                                <th class="pb-3">Shop Name</th>
                                <th class="pb-3">Shop Description</th>
                                <th class="pb-3 text-center">Commission Rate</th>
                                <th class="pb-3 text-center">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            @foreach($vendors as $vendor)
                                @php
                                    $profile = $vendor->vendorProfile;
                                @endphp
                                <tr>
                                    <!-- Vendor details -->
                                    <td class="py-4">
                                        <p class="font-bold text-white text-sm">{{ $vendor->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $vendor->email }}</p>
                                    </td>
                                    
                                    <!-- Shop Name -->
                                    <td class="py-4 font-bold text-slate-200">
                                        {{ $profile ? $profile->shop_name : 'No Shop Registered' }}
                                    </td>
                                    
                                    <!-- Description -->
                                    <td class="py-4 max-w-xs truncate text-slate-450">
                                        {{ $profile ? $profile->description : 'N/A' }}
                                    </td>
                                    
                                    <!-- Commission Rate -->
                                    <td class="py-4 text-center font-bold text-slate-350">
                                        {{ $profile ? $profile->commission_rate . '%' : 'N/A' }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="py-4 text-center">
                                        @if($profile && $profile->is_verified)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-500">
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
                                                    class="px-3 py-1.5 rounded-lg font-bold text-[10px] border transition"
                                                    class="toggle-button"
                                                    style="background-color: {{ $profile->is_verified ? '#991b1b' : '#312e81' }}; border-color: {{ $profile->is_verified ? '#991b1b' : '#312e81' }}; color: #ffffff;">
                                                    {{ $profile->is_verified ? 'Unverify' : 'Verify Shop' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-600 font-semibold italic">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
@endsection
