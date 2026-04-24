@extends('customer.layout')

@section('title', 'Invoice Details')

@section('content')
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Invoice Details</h1>
                        <p class="mt-2 text-gray-600">Invoice {{ $invoice->invoice_number }}</p>
                    </div>
                    <a href="{{ route('customer.invoices.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Back to Invoices
                    </a>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b pb-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h2>
                            <p class="text-gray-600 mt-1">Order: {{ $invoice->order->order_number }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $invoice->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Invoice Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Invoice Date:</span>
                                <span class="font-medium">{{ $invoice->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($invoice->issue_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Issue Date:</span>
                                <span class="font-medium">{{ $invoice->issue_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($invoice->due_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Due Date:</span>
                                <span class="font-medium">{{ $invoice->due_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($invoice->paid_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Paid Date:</span>
                                <span class="font-medium">{{ $invoice->paid_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-bold text-lg">RM{{ number_format($invoice->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-medium">{{ $invoice->order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span class="font-medium">{{ $invoice->order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $invoice->order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $invoice->order->status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Customer:</span>
                                <span class="font-medium">{{ $invoice->order->customer_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($invoice->payments->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-4">
                    @foreach($invoice->payments as $payment)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">Payment #{{ $payment->id }}</p>
                                <p class="text-sm text-gray-600">Amount: RM{{ number_format($payment->amount, 2) }}</p>
                                <p class="text-sm text-gray-600">Method: {{ $payment->payment_method }}</p>
                                <p class="text-sm text-gray-600">Date: {{ $payment->payment_date->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Shipment Information -->
            @if($invoice->shipment)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tracking Number:</span>
                            <span class="font-medium">{{ $invoice->shipment->tracking_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Courier:</span>
                            <span class="font-medium">{{ $invoice->shipment->courier_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $invoice->shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $invoice->shipment->status }}
                            </span>
                        </div>
                        @if($invoice->shipment->shipped_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipped Date:</span>
                            <span class="font-medium">{{ $invoice->shipment->shipped_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                        @if($invoice->shipment->delivered_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivered Date:</span>
                            <span class="font-medium">{{ $invoice->shipment->delivered_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('customer.shipments.track', $invoice->shipment) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block mb-2">
                            Track Shipment
                        </a>
                        @if($invoice->shipment->pod_file_path)
                        <a href="{{ asset('storage/' . $invoice->shipment->pod_file_path) }}" 
                           target="_blank"
                           class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                            View Proof of Delivery
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                @if($invoice->status !== 'paid')
                <a href="{{ route('customer.payments.create', $invoice) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Pay Now
                </a>
                @endif
                <a href="{{ route('customer.orders.show', $invoice->order) }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    View Order Details
                </a>
                @if($invoice->shipment)
                <a href="{{ route('customer.shipments.track', $invoice->shipment) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Track Shipment
                </a>
                @endif
            </div>
@endsection
