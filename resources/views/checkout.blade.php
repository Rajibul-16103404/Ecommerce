@extends('layouts.app')

@section('title', 'Secure Checkout - ShopHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8">Checkout</h1>

        <form method="POST" action="{{ route('orders.store') }}" x-data="{ paymentMethod: 'cod' }">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Billing & Shipping Form -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-8">
                    
                    <!-- Section 1: Shipping Details -->
                    <div class="space-y-5">
                        <h3 class="font-extrabold text-slate-800 text-lg border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-black mr-2">1</span>
                            Shipping Information
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="shipping_address" class="block text-xs font-bold uppercase text-slate-400 mb-2">Street Address</label>
                                <input type="text" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="House number, Street name, Apartment, etc.">
                            </div>
                            <div>
                                <label for="shipping_city" class="block text-xs font-bold uppercase text-slate-400 mb-2">City</label>
                                <input type="text" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="e.g. Dhaka, Chittagong">
                            </div>
                            <div>
                                <label for="shipping_zip" class="block text-xs font-bold uppercase text-slate-400 mb-2">ZIP / Postal Code</label>
                                <input type="text" id="shipping_zip" name="shipping_zip" value="{{ old('shipping_zip') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="e.g. 1207">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="shipping_phone" class="block text-xs font-bold uppercase text-slate-400 mb-2">Phone Number</label>
                                <input type="text" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none placeholder-slate-350"
                                    placeholder="e.g. +88017XXXXXXXX">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Payment Methods (Manual verification) -->
                    <div class="space-y-5">
                        <h3 class="font-extrabold text-slate-800 text-lg border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-black mr-2">2</span>
                            Select Payment Method
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Cash on Delivery -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'cod' ? 'ring-2 ring-indigo-600 bg-indigo-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="cod" class="sr-only" @click="paymentMethod = 'cod'" checked>
                                <i class="fas fa-hand-holding-dollar text-slate-400 text-xl mb-3" :class="paymentMethod === 'cod' ? 'text-indigo-600' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Cash on Delivery</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Pay at your doorstep upon receiving package.</span>
                            </label>

                            <!-- bKash -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'bkash' ? 'ring-2 ring-indigo-600 bg-indigo-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="bkash" class="sr-only" @click="paymentMethod = 'bkash'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'bkash' ? 'text-pink-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">bKash (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via bKash.</span>
                            </label>

                            <!-- Nagad -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'nagad' ? 'ring-2 ring-indigo-600 bg-indigo-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="nagad" class="sr-only" @click="paymentMethod = 'nagad'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'nagad' ? 'text-orange-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Nagad (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via Nagad.</span>
                            </label>

                            <!-- Rocket -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'rocket' ? 'ring-2 ring-indigo-600 bg-indigo-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="rocket" class="sr-only" @click="paymentMethod = 'rocket'">
                                <i class="fas fa-mobile-screen text-slate-400 text-xl mb-3" :class="paymentMethod === 'rocket' ? 'text-purple-500' : ''"></i>
                                <span class="font-bold text-slate-800 text-sm">Rocket (Mobile)</span>
                                <span class="text-[10px] text-slate-400 mt-1 leading-snug">Manual mobile transfer via Rocket.</span>
                            </label>

                            <!-- Bank Transfer -->
                            <label class="relative flex flex-col p-5 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition"
                                :class="paymentMethod === 'bank_transfer' ? 'ring-2 ring-indigo-600 bg-indigo-50/10 border-transparent' : ''">
                                <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" @click="paymentMethod = 'bank_transfer'">
                                <i class="fas fa-building-columns text-slate-400 text-xl mb-3" :class="paymentMethod === 'bank_transfer' ? 'text-blue-600' : ''"></i>
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
                                        <p>2. Send the exact grand total amount <span class="font-bold text-slate-800">${{ number_format($total, 2) }}</span> to our Wallet Number: <span class="font-bold text-indigo-600">+8801777777777</span>.</p>
                                        <p>3. Input your Transaction details below once the transaction completes successfully.</p>
                                    </div>
                                </template>

                                <!-- Bank instructions -->
                                <template x-if="paymentMethod === 'bank_transfer'">
                                    <div class="text-xs text-slate-500 leading-normal space-y-1">
                                        <p>1. Transfer the exact amount <span class="font-bold text-slate-800">${{ number_format($total, 2) }}</span> to our Bank Account:</p>
                                        <p class="pl-4 font-bold text-slate-700">Bank Name: Demo Premium Bank Ltd</p>
                                        <p class="pl-4 font-bold text-slate-700">Account Name: ShopHub Ltd</p>
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
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none"
                                        placeholder="e.g. +88017XXXXXXXX / Account No">
                                </div>
                                <div>
                                    <label for="transaction_id" class="block text-xs font-bold uppercase text-slate-400 mb-2">Transaction ID (TxnID) / Ref</label>
                                    <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}" 
                                        ::required="paymentMethod !== 'cod'"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none"
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
                                    <span class="font-bold text-slate-700">${{ number_format($item->product->discounted_price * $item->quantity, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="space-y-3 pt-4 border-t border-slate-100 text-sm font-medium">
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal</span>
                                <span class="text-slate-800">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Shipping costs</span>
                                <span class="text-slate-800">
                                    @if($shipping == 0)
                                        <span class="text-green-600 font-bold">FREE</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <hr class="border-slate-100">
                            <div class="flex justify-between text-base font-bold text-slate-900">
                                <span>Total Price</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-150 transition transform hover:-translate-y-0.5 flex items-center justify-center">
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
