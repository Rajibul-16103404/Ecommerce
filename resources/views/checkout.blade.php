@extends('layouts.app')

@section('title', 'Secure Checkout - Shopee')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8">Checkout</h1>

        <form method="POST" action="{{ route('orders.store') }}" x-data="{ paymentMethod: 'cod', createAccount: false, shippingFee: {{ $shipping }}, subtotal: {{ $subtotal }} }">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Billing & Shipping Form -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-8">
                    
                    @guest
                    <!-- Section: Account Details (Guest only) -->
                    <div class="space-y-5">
                        <h3 class="font-extrabold text-slate-800 text-lg border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-650 text-xs font-black mr-2">1</span>
                            Account Information
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase text-slate-400 mb-2">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="e.g. John Doe">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase text-slate-400 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="john@example.com">
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label class="inline-flex items-center mt-2 cursor-pointer">
                                    <input type="checkbox" name="create_account" value="1" x-model="createAccount" class="rounded border-slate-300 text-emerald-650 focus:ring-emerald-500 w-4 h-4">
                                    <span class="ml-2.5 text-sm font-bold text-slate-700">Create an account?</span>
                                </label>
                            </div>

                            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="createAccount" x-transition style="display: none;">
                                <div>
                                    <label for="password" class="block text-xs font-bold uppercase text-slate-400 mb-2">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350"
                                        placeholder="••••••••">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold uppercase text-slate-400 mb-2">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-350"
                                        placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest

                    <!-- Section: Shipping Details -->
                    <div class="space-y-5">
                        <h3 class="font-extrabold text-slate-800 text-lg border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-black mr-2">{{ auth()->check() ? '1' : '2' }}</span>
                            <i class="fas fa-truck text-emerald-500 mr-1.5"></i> Shipping Information
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Street Address (full width) --}}
                            <div class="sm:col-span-2">
                                <label for="shipping_address" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-house-chimney mr-1"></i> Street Address <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="shipping_address" name="shipping_address"
                                    value="{{ old('shipping_address', auth()->check() ? auth()->user()->address : '') }}"
                                    required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400"
                                    placeholder="House / Flat no., Road no., Block, Street name…">
                                @error('shipping_address')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Area / Neighbourhood --}}
                            <div>
                                <label for="shipping_area" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-location-dot mr-1"></i> Area / Neighbourhood
                                </label>
                                <input type="text" id="shipping_area" name="shipping_area"
                                    value="{{ old('shipping_area') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400"
                                    placeholder="e.g. Dhanmondi, Gulshan, Uttara…">
                                @error('shipping_area')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nearest Landmark --}}
                            <div>
                                <label for="shipping_landmark" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-map-pin mr-1"></i> Nearest Landmark
                                </label>
                                <input type="text" id="shipping_landmark" name="shipping_landmark"
                                    value="{{ old('shipping_landmark') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400"
                                    placeholder="e.g. Beside Bashundhara City, Behind Farmgate…">
                                @error('shipping_landmark')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City / Location --}}
                            <div>
                                <label for="shipping_city" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-city mr-1"></i> City / Location <span class="text-red-400">*</span>
                                </label>
                                <select id="shipping_city" name="shipping_city" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                    x-on:change="shippingFee = parseFloat($event.target.selectedOptions[0].getAttribute('data-fee'))">
                                    @foreach($shippingLocations as $loc)
                                        <option value="{{ $loc->name }}" data-fee="{{ $loc->fee }}"
                                            {{ old('shipping_city') == $loc->name ? 'selected' : '' }}>
                                            {{ $loc->name }} — ৳{{ number_format($loc->fee, 2) }} shipping
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_city')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ZIP Code --}}
                            <div>
                                <label for="shipping_zip" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-envelope-open mr-1"></i> ZIP / Postal Code <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="shipping_zip" name="shipping_zip"
                                    value="{{ old('shipping_zip') }}"
                                    required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400"
                                    placeholder="e.g. 1207">
                                @error('shipping_zip')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Primary Phone --}}
                            <div>
                                <label for="shipping_phone" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-phone mr-1"></i> Contact Phone <span class="text-red-400">*</span>
                                </label>
                                <input type="tel" id="shipping_phone" name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->check() ? auth()->user()->phone : '') }}"
                                    required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400"
                                    placeholder="+8801XXXXXXXXX">
                                @error('shipping_phone')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Delivery Notes (full width) --}}
                            <div class="sm:col-span-2">
                                <label for="shipping_notes" class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                    <i class="fas fa-note-sticky mr-1"></i> Delivery Instructions
                                    <span class="text-slate-300 font-normal normal-case ml-1">(optional)</span>
                                </label>
                                <textarea id="shipping_notes" name="shipping_notes" rows="3"
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none placeholder-slate-400 resize-none"
                                    placeholder="Gate code, preferred delivery time, leave at door, call before arriving, etc.">{{ old('shipping_notes') }}</textarea>
                            </div>

                        </div>

                        {{-- Shipping estimate notice --}}
                        <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-3">
                            <i class="fas fa-circle-info text-emerald-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-emerald-700 leading-relaxed">
                                <strong>Estimated delivery:</strong> Dhaka – 1 to 2 business days &nbsp;|&nbsp; Outside Dhaka – 3 to 5 business days.
                                Delivery times start from the moment your payment is verified.
                            </p>
                        </div>
                    </div>

                    <!-- Section: Payment Methods -->
                    <div class="space-y-5">
                        <h3 class="font-extrabold text-slate-800 text-lg border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-650 text-xs font-black mr-2">{{ auth()->check() ? '2' : '3' }}</span>
                            Select Payment Method
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Cash on Delivery -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'cod' ? 'ring-2 ring-emerald-600 bg-emerald-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="cod" class="sr-only" @click="paymentMethod = 'cod'" checked>
                                <i class="fas fa-hand-holding-dollar text-slate-400 text-xl mb-3" :class="paymentMethod === 'cod' ? 'text-emerald-600' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Cash on Delivery</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Pay at your doorstep upon receiving package.</span>
                            </label>

                            <!-- bKash -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'bkash' ? 'ring-2 ring-orange-500 bg-orange-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="bkash" class="sr-only" @click="paymentMethod = 'bkash'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'bkash' ? 'text-pink-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">bKash (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via bKash.</span>
                            </label>

                            <!-- Nagad -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'nagad' ? 'ring-2 ring-orange-500 bg-orange-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="nagad" class="sr-only" @click="paymentMethod = 'nagad'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'nagad' ? 'text-orange-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Nagad (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via Nagad.</span>
                            </label>

                            <!-- Rocket -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'rocket' ? 'ring-2 ring-orange-500 bg-orange-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="rocket" class="sr-only" @click="paymentMethod = 'rocket'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'rocket' ? 'text-purple-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Rocket (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via Rocket.</span>
                            </label>

                            <!-- Bank Transfer -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'bank_transfer' ? 'ring-2 ring-emerald-600 bg-emerald-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" @click="paymentMethod = 'bank_transfer'">
                                <i class="fas fa-building-columns text-slate-400 text-xl mb-3" :class="paymentMethod === 'bank_transfer' ? 'text-emerald-600' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Bank Wire Transfer</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Direct Bank deposit or online wire transfer.</span>
                            </label>
                        </div>

                        <!-- Manual Gateway Instructions & Inputs -->
                        <div x-show="paymentMethod !== 'cod'" class="p-6 bg-slate-100/60 rounded-3xl border border-slate-200/40 space-y-5" style="display: none;" x-transition>
                            <div class="space-y-2.5">
                                <h4 class="font-bold text-slate-800 text-sm">Payment Instructions:</h4>
                                
                                <!-- Mobile instructions -->
                                <template x-if="['bkash', 'nagad', 'rocket'].includes(paymentMethod)">
                                    <div class="text-xs text-slate-500 leading-normal space-y-1">
                                        <p>1. Open your Mobile Wallet and navigate to Send Money / Merchant Pay.</p>
                                        <p>2. Send the exact grand total amount <span class="font-bold text-slate-800">৳<span x-text="(subtotal + shippingFee).toFixed(2)"></span></span> to our Wallet Number: <span class="font-bold text-emerald-650">+8801777777777</span>.</p>
                                        <p>3. Input your Transaction details below once the transaction completes successfully.</p>
                                    </div>
                                </template>

                                <!-- Bank instructions -->
                                <template x-if="paymentMethod === 'bank_transfer'">
                                    <div class="text-xs text-slate-500 leading-normal space-y-1">
                                        <p>1. Transfer the exact amount <span class="font-bold text-slate-800">৳<span x-text="(subtotal + shippingFee).toFixed(2)"></span></span> to our Bank Account:</p>
                                        <p class="pl-4 font-bold text-slate-700">Bank Name: Demo Premium Bank Ltd</p>
                                        <p class="pl-4 font-bold text-slate-700">Account Name: Shopee Ltd</p>
                                        <p class="pl-4 font-bold text-slate-700">Account Number: 1234-5678-9012</p>
                                        <p class="pl-4 font-bold text-slate-700">Routing Number: 999888777</p>
                                        <p>2. Save the deposit receipt or reference ID and enter the details below.</p>
                                    </div>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_sender" class="block text-xs font-bold uppercase text-slate-400 mb-2">Sender Phone / Account</label>
                                    <input type="text" id="payment_sender" name="payment_sender" value="{{ old('payment_sender') }}" 
                                        ::required="paymentMethod !== 'cod'"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                        placeholder="e.g. +88017XXXXXXXX / Account No">
                                </div>
                                <div>
                                    <label for="transaction_id" class="block text-xs font-bold uppercase text-slate-400 mb-2">Transaction ID (TxnID) / Ref</label>
                                    <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}" 
                                        ::required="paymentMethod !== 'cod'"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 text-sm focus:outline-none"
                                        placeholder="e.g. TRx8291079">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Side Summary -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
                        <h3 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4">Order Items</h3>

                        <ul class="divide-y divide-slate-100 max-h-56 overflow-y-auto pr-1">
                            @foreach($cartItems as $item)
                                <li class="flex py-4 first:pt-0 last:pb-0 items-center justify-between text-xs">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 rounded-lg bg-slate-50 text-slate-600 font-bold border border-slate-150 flex items-center justify-center mr-2">
                                            {{ $item->quantity }}
                                        </span>
                                        <span class="font-bold text-slate-800 line-clamp-1 max-w-[150px]">{{ $item->product->name }}</span>
                                    </div>
                                    <span class="font-bold text-slate-700">৳{{ number_format($item->product->discounted_price * $item->quantity, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="space-y-3 pt-4 border-t border-slate-100 text-sm font-medium">
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal</span>
                                <span class="text-slate-800">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Shipping costs</span>
                                <span class="text-slate-800">
                                    ৳<span x-text="shippingFee.toFixed(2)"></span>
                                </span>
                            </div>
                            <hr class="border-slate-100">
                            <div class="flex justify-between text-base font-bold text-slate-900">
                                <span>Total Price</span>
                                <span class="text-emerald-600">৳<span x-text="(subtotal + shippingFee).toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-600 to-orange-500 hover:from-emerald-700 hover:to-orange-600 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/10 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fas fa-lock mr-2 text-xs"></i> Complete Order
                    </button>
                    <a href="{{ route('cart.index') }}" class="w-full py-4 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl transition flex items-center justify-center hover:bg-slate-50">
                        Back to Shopping Cart
                    </a>
                </div>

            </div>
        </form>
    </div>
@endsection
