@extends('layouts.app')

@section('title', 'Create Shipment')

@section('content')
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Shipment</h1>
                    <p class="mt-2 text-gray-600">Create shipment for invoice {{ $invoice->invoice_number }}</p>
                </div>
                <a href="{{ route('admin.invoices.show', $invoice) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Invoice
                </a>
            </div>
        </div>

        <!-- Shipment Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Invoice Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Invoice Number:</p>
                        <p class="font-medium">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Customer:</p>
                        <p class="font-medium">{{ $invoice->order ? $invoice->order->customer_name : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Amount:</p>
                        <p class="font-medium">RM{{ number_format($invoice->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status:</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $invoice->status }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.shipments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="courier_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Courier Name *
                        </label>
                        <select id="courier_name" name="courier_name" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select courier</option>
                            <option value="PosLaju">PosLaju</option>
                            <option value="J&T Express">J&T Express</option>
                            <option value="DHL">DHL</option>
                            <option value="FedEx">FedEx</option>
                            <option value="GDex">GDex</option>
                            <option value="Ninja Van">Ninja Van</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Address *
                        </label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Enter shipping address">{{ $invoice->order ? $invoice->order->customer_address : '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Add any additional notes (optional)"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.invoices.show', $invoice) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Create Shipment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
