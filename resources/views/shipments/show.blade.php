@extends('layouts.app')

@section('title', 'Shipment Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Shipment Details</h1>
            <p class="mt-1 text-sm text-gray-600">Tracking: {{ $shipment->tracking_number }}</p>
        </div>

        <!-- Shipment Information -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Shipment Information</h3>
                
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                        <dd class="text-sm text-gray-900">{{ $shipment->tracking_number }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Invoice</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('admin.invoices.show', $shipment->invoice) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $shipment->invoice->invoice_number }}
                            </a>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="text-sm text-gray-900">{{ $shipment->invoice->order->customer_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Courier</dt>
                        <dd class="text-sm text-gray-900">{{ $shipment->courier_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $shipment->status }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                        <dd class="text-sm text-gray-900">{{ $shipment->created_at->format('M d, Y') }}</dd>
                    </div>
                    @if($shipment->shipped_date)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Shipped Date</dt>
                            <dd class="text-sm text-gray-900">{{ $shipment->shipped_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    @if($shipment->delivered_date)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Delivered Date</dt>
                            <dd class="text-sm text-gray-900">{{ $shipment->delivered_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Shipping Address</h4>
                    <p class="text-sm text-gray-600">{{ $shipment->shipping_address }}</p>
                </div>

                @if($shipment->notes)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Notes</h4>
                        <p class="text-sm text-gray-600">{{ $shipment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Proof of Delivery -->
        @if($shipment->pod_file_path)
            <div class="bg-white shadow sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Proof of Delivery</h3>
                    <a href="{{ asset('storage/' . $shipment->pod_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        View Proof of Delivery
                    </a>
                </div>
            </div>
        @endif

        <!-- Status Update Form -->
        @if($shipment->status !== 'delivered')
            <div class="bg-white shadow sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Status</h3>
                    
                    <form method="POST" action="{{ route('admin.shipments.updateStatus', $shipment) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending" {{ $shipment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ $shipment->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Upload POD -->
        @if($shipment->status === 'delivered' && !$shipment->pod_file_path)
            <div class="bg-white shadow sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Upload Proof of Delivery</h3>
                    
                    <form method="POST" action="{{ route('admin.shipments.uploadPOD', $shipment) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="pod_file" class="block text-sm font-medium text-gray-700">
                                Proof of Delivery File
                            </label>
                            <input type="file" name="pod_file" id="pod_file" required
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Upload POD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.shipments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Shipments
            </a>
        </div>
    </div>
</div>
@endsection
