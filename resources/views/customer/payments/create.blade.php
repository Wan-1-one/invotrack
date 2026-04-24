@extends('customer.layout')

@section('title', 'Make Payment')

@section('content')
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Make Payment</h1>
                                <p class="mt-2 text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
                            </div>
                            <a href="{{ route('customer.invoices.show', $invoice) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                                Back to Invoice
                            </a>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Invoice Number</p>
                                <p class="font-medium">{{ $invoice->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-medium">{{ $invoice->order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-medium text-lg">RM{{ number_format($invoice->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $invoice->status }}
                                </span>
                            </div>
                        </div>
                        
                        @if($invoice->payments->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Previous Payments</p>
                            @foreach($invoice->payments as $payment)
                            <div class="flex justify-between text-sm">
                                <span>Payment #{{ $payment->id }} ({{ $payment->payment_method }})</span>
                                <span>RM{{ number_format($payment->amount, 2) }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between font-medium mt-2 pt-2 border-t border-gray-200">
                                <span>Remaining Balance</span>
                                <span>RM{{ number_format($invoice->amount - $invoice->payments->sum('amount'), 2) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Form -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                        
                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                            <div class="text-sm text-red-800">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('customer.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">
                                        Payment Amount (RM)
                                    </label>
                                    <input type="number" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="{{ $invoice->amount - $invoice->payments->sum('amount') }}"
                                           value="{{ $invoice->amount - $invoice->payments->sum('amount') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                           required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Maximum amount: RM{{ number_format($invoice->amount - $invoice->payments->sum('amount'), 2) }}
                                    </p>
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">
                                        Payment Method
                                    </label>
                                    <select id="payment_method" 
                                            name="payment_method" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            required>
                                        <option value="">Select a payment method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="online_banking">Online Banking</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">
                                        Payment Date
                                    </label>
                                    <input type="date" 
                                           id="payment_date" 
                                           name="payment_date" 
                                           value="{{ now()->format('Y-m-d') }}"
                                           max="{{ now()->format('Y-m-d') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                           required>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-4">
                                <a href="{{ route('customer.invoices.show', $invoice) }}" 
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
@endsection
