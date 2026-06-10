@extends('layouts.admin')

@section('title', 'Manage Shipping Fees - Shopee')
@section('page_title', 'Shipping Fees')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Shipping Fees & Locations</h1>
            <p class="text-xs text-slate-400 mt-1">Configure shipping rates for different delivery locations/cities.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Shipping list table -->
            <div class="lg:col-span-8 bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-4 mb-6">Active Shipping Locations</h3>
                
                @if($locations->isEmpty())
                    <p class="text-slate-400 text-sm text-center py-8">No shipping locations configured.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-slate-655">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400">
                                    <th class="pb-3">Location Name</th>
                                    <th class="pb-3">Shipping Fee</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($locations as $loc)
                                    <tr>
                                        <td class="py-4 font-bold text-slate-800 text-sm">
                                            {{ $loc->name }}
                                        </td>
                                        <td class="py-4 font-bold text-emerald-600 text-sm">
                                            ৳{{ number_format($loc->fee, 2) }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <form method="POST" action="{{ route('admin.shipping.destroy', $loc->id) }}" onsubmit="return confirm('Remove this shipping location?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-white border border-slate-200 text-rose-600 hover:border-rose-500 hover:bg-rose-50 rounded-lg font-bold transition shadow-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($locations->hasPages())
                    <div class="pt-4 border-t border-slate-100 mt-4">
                        {{ $locations->links() }}
                    </div>
                @endif
            </div>

            <!-- Create location form -->
            <div class="lg:col-span-4 bg-white border border-slate-150/85 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-4 mb-6">Add Location</h3>
                
                <form method="POST" action="{{ route('admin.shipping.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase text-slate-400 mb-2">Location / City Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs focus:outline-none text-slate-800 placeholder-slate-400"
                            placeholder="e.g. Sylhet">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fee" class="block text-xs font-bold uppercase text-slate-400 mb-2">Shipping Fee (৳)</label>
                        <input type="number" id="fee" name="fee" value="{{ old('fee') }}" step="0.01" min="0" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs focus:outline-none text-slate-800 placeholder-slate-400"
                            placeholder="e.g. 80.00">
                        @error('fee') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-lg shadow-emerald-500/10">
                        Add Location
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
