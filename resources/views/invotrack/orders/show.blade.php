@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                    <p class="mt-2 text-gray-600">Order {{ $order->order_number }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h2>
                        <p class="text-gray-600 mt-1">Created: {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ $order->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $order->customer_email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium">{{ $order->customer_phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Address:</span>
                            <span class="font-medium text-right max-w-xs">{{ $order->customer_address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-lg">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        @if($order->notes)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Notes:</span>
                            <span class="font-medium">{{ $order->notes }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Information -->
        @if($order->invoice)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Invoice Number:</span>
                        <span class="font-medium">{{ $order->invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Invoice Amount:</span>
                        <span class="font-medium">RM{{ number_format($order->invoice->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Invoice Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($order->invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $order->invoice->status }}
                        </span>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.invoices.show', $order->invoice) }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block mb-2">
                        View Invoice Details
                    </a>
                    @if($order->invoice->status === 'draft')
                        <form action="{{ route('admin.invoices.issue', $order->invoice) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium block">
                                Issue Invoice
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Shipment Information -->
        @if($order->invoice && $order->invoice->shipment)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tracking Number:</span>
                        <span class="font-medium">{{ $order->invoice->shipment->tracking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Courier:</span>
                        <span class="font-medium">{{ $order->invoice->shipment->courier_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $order->invoice->shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                               ($order->invoice->shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $order->invoice->shipment->status }}
                        </span>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.shipments.track', $order->invoice->shipment) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                        Track Shipment
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            @if($order->status === 'pending' && !$order->invoice)
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Delete Order
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.orders.edit', $order) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Edit Order
            </a>
        </div>
    </div>
@endsection
