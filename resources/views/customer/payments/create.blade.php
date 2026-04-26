@extends('customer.layout')

@section('title', 'Make Payment')

@section('content')
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Make Payment</h1>
                        <p class="mt-2 text-gray-600">Complete payment for your invoice</p>
                    </div>
                    <a href="{{ route('customer.invoices.show', $invoice) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        Back to Invoice
                    </a>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Invoice Summary -->
                <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Invoice Number:</p>
                            <p class="font-medium">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Order Number:</p>
                            <p class="font-medium">{{ $invoice->order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Amount:</p>
                            <p class="font-medium text-lg">RM{{ number_format($invoice->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Amount Paid:</p>
                            <p class="font-medium">RM{{ number_format($invoice->payments->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Remaining Amount:</span>
                            <span class="text-lg font-bold text-purple-600">
                                RM{{ number_format($invoice->amount - $invoice->payments->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form action="{{ route('customer.payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method *
                            </label>
                            <select id="payment_method" name="payment_method" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                <option value="">Select payment method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="cash">Cash</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Payment Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Amount (RM) *
                            </label>
                            <input type="number" id="amount" name="amount" step="0.01" min="0.01" required readonly
                                   value="{{ $invoice->amount - $invoice->payments->sum('amount') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Date *
                            </label>
                            <input type="date" id="payment_date" name="payment_date" required readonly
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <!-- Reference -->
                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Reference
                            </label>
                            <input type="text" id="reference_number" name="reference_number"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                   placeholder="Transaction reference (optional)">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                  placeholder="Additional notes (optional)"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit" 
                                class="w-full bg-green-600 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Process Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection
