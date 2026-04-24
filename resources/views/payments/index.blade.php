@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        </div>

        <!-- Payments Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            RM{{ number_format($payment->amount, 2) }} - {{ $payment->payment_method }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Invoice: {{ $payment->invoice->invoice_number }} - {{ $payment->invoice->order->customer_name }}
                                        </p>
                                        @if($payment->transaction_reference)
                                            <p class="text-sm text-gray-500">
                                                Reference: {{ $payment->transaction_reference }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $payment->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $payment->status }}
                                    </span>
                                    @if($payment->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                Verify
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-gray-500">
                        No payments found. Payments are recorded for issued invoices.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
