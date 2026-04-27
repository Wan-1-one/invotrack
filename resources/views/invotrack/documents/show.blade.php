@extends('layouts.app')

@section('title', 'Customs Document Details')

@section('content')
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Customs Document</h1>
                    <p class="mt-2 text-gray-600">Document {{ $document->document_number }}</p>
                </div>
                <a href="{{ request()->is('admin/*') ? route('admin.orders.show', $document->order) : route('customer.orders.show', $document->order) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Order
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Document Status Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $document->document_number }}</h2>
                    <p class="text-gray-600 mt-1">Created: {{ $document->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $document->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $document->formatted_status }}
                    </span>
                </div>
            </div>

            @if($document->status === 'approved')
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 font-medium">Approved by Customs</span>
                    </div>
                    <p class="text-green-700 text-sm mt-1">Approved at: {{ $document->approved_at->format('M d, Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-medium">{{ $document->order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer Name:</span>
                        <span class="font-medium">{{ $document->order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer Email:</span>
                        <span class="font-medium">{{ $document->order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer Phone:</span>
                        <span class="font-medium">{{ $document->order->customer_phone ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Quantity:</span>
                        <span class="font-medium">{{ $document->order->quantity }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Product Name:</span>
                        <span class="font-medium">{{ $document->order->name_of_products }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-lg">RM{{ number_format($document->order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipment Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transportation Type:</span>
                        <span class="font-medium">{{ str_replace('_', ' ', $document->order->transportation_type) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Destination:</span>
                        <span class="font-medium">{{ str_replace('_', ' ', $document->order->delivery_destination) }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cargo Size:</span>
                        <span class="font-medium">{{ ucfirst($document->order->cargo_size) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type of Goods:</span>
                        <span class="font-medium">{{ str_replace('_', ' ', $document->order->type_of_goods) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Content -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Content</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">{{ $document->content }}</pre>
            </div>
        </div>

        <!-- Admin Actions -->
        @if(request()->is('admin/*'))
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>
                @if($document->status === 'pending')
                    <form action="{{ route('admin.documents.sendToCustoms', $document) }}" method="POST" onsubmit="return confirm('Are you sure you want to send this document to customs for approval?')">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium">
                            🛃 Send to Customs
                        </button>
                    </form>
                @else
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">✅ Approved by Customs</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Timeline</h3>
            <div class="space-y-4">
                <!-- Document Generated -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Document Generated</p>
                        <p class="text-sm text-gray-500">{{ $document->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <!-- Sent to Customs / Approved -->
                @if($document->status === 'approved')
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Approved by Customs</p>
                            <p class="text-sm text-gray-500">{{ $document->approved_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Pending Customs Approval</p>
                            <p class="text-sm text-gray-500">Waiting for admin to send to customs</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
