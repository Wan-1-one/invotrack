@extends('layouts.app')

@section('title', 'Shipments')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Shipments</h1>
        </div>

        <!-- Shipments Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($shipments as $shipment)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div>
                                        <a href="{{ route('admin.shipments.track', $shipment) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $shipment->tracking_number }}
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            Invoice: {{ $shipment->invoice->invoice_number }} - {{ $shipment->invoice->order->customer_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Courier: {{ $shipment->courier_name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            {{ $shipment->created_at->format('M d, Y') }}
                                        </p>
                                        @if($shipment->shipped_date)
                                            <p class="text-sm text-gray-500">
                                                Shipped: RM {{ number_format($shipment->shipped_date->format('M d, Y')) }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           ($shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $shipment->status }}
                                    </span>
                                    @if($shipment->pod_file_path)
                                        <a href="{{ asset('storage/' . $shipment->pod_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            POD
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-gray-500">
                        No shipments found. Shipments are created after invoices are paid.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($shipments->hasPages())
            <div class="mt-6">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
