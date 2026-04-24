@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Order RM{{ number_format($order->order_number) }}</h1>
            <p class="mt-1 text-sm text-gray-600">Update the order details below.</p>
        </div>

        <!-- Order Form -->
        <form method="POST" action="{{ route('orders.update', $order) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Customer Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Customer Information</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">
                                Customer Name *
                            </label>
                            <input type="text" name="customer_name" id="customer_name" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="{{ old('customer_name', $order->customer_name) }}">
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">
                                Email Address *
                            </label>
                            <input type="email" name="customer_email" id="customer_email" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="{{ old('customer_email', $order->customer_email) }}">
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">
                                Phone Number
                            </label>
                            <input type="tel" name="customer_phone" id="customer_phone"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="{{ old('customer_phone', $order->customer_phone) }}">
                        </div>

                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700">
                                Total Amount (RM) *
                            </label>
                            <input type="number" name="total_amount" id="total_amount" step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="{{ old('total_amount', $order->total_amount) }}">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Order Status *
                            </label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $order->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="customer_address" class="block text-sm font-medium text-gray-700">
                            Shipping Address *
                        </label>
                        <textarea name="customer_address" id="customer_address" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('customer_address', $order->customer_address) }}</textarea>
                    </div>

                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Order Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.orders.show', $order) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
