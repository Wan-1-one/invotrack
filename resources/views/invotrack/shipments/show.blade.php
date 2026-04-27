@extends('layouts.app')

@section('title', 'Shipment Details')

@section('content')
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Shipment Details</h1>
                    <p class="mt-2 text-gray-600">Tracking Number: {{ $shipment->tracking_number }}</p>
                </div>
                <a href="{{ route('admin.shipments.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Shipments
                </a>
            </div>
        </div>

        <!-- Shipment Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tracking Number:</span>
                            <span class="font-medium">{{ $shipment->tracking_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Courier:</span>
                            <span class="font-medium">{{ $shipment->courier_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $shipment->status }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created Date:</span>
                            <span class="font-medium">{{ $shipment->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($shipment->shipped_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipped Date:</span>
                            <span class="font-medium">{{ $shipment->shipped_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                        @if($shipment->delivered_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivered Date:</span>
                            <span class="font-medium">{{ $shipment->delivered_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium">{{ $shipment->invoice && $shipment->invoice->order ? $shipment->invoice->order->order_number : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Invoice Number:</span>
                            <span class="font-medium">{{ $shipment->invoice ? $shipment->invoice->invoice_number : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer:</span>
                            <span class="font-medium">{{ $shipment->invoice && $shipment->invoice->order ? $shipment->invoice->order->customer_name : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping Address:</span>
                            <span class="font-medium text-right max-w-xs">{{ $shipment->shipping_address }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customs Document Information -->
        @if($shipment->invoice && $shipment->invoice->order && $shipment->invoice->order->document)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customs Document</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Document Number:</span>
                        <span class="font-medium">{{ $shipment->invoice->order->document->document_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $shipment->invoice->order->document->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $shipment->invoice->order->document->formatted_status }}
                        </span>
                    </div>
                    @if($shipment->invoice->order->document->approved_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Approved At:</span>
                        <span class="font-medium">{{ $shipment->invoice->order->document->approved_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.documents.show', $shipment->invoice->order->document) }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                        👉 View Customs Document
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.shipments.track', $shipment) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Track Shipment
            </a>
            @if($shipment->pod_file_path)
            <a href="{{ asset('storage/' . $shipment->pod_file_path) }}" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Proof of Delivery
            </a>
            @endif
        </div>
    </div>
@endsection
