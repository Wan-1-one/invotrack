@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="px-4 py-6 sm:px-0 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Orders</h1>
            <p class="text-sm text-gray-500">Manage and track all customer orders</p>
        </div>

        <a href="{{ route('admin.orders.create') }}"
           class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
            + Create Order
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">

            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search order, customer..."
                   class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">

            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">

            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">

            <select name="sort_by"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created</option>
                <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Amount</option>
                <option value="order_number" {{ request('sort_by') == 'order_number' ? 'selected' : '' }}>Order #</option>
            </select>

            <select name="sort_order"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
            </select>

            <div class="flex gap-2 md:col-span-6">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                    Apply
                </button>

                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Orders List -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">

        @forelse($orders as $order)
        <a href="{{ route('admin.orders.show', $order) }}"
           class="block px-6 py-5 border-b border-gray-100 hover:bg-gray-50 transition">

            <div class="flex justify-between items-center">

                <!-- Left -->
                <div>
                    <p class="text-sm font-semibold text-indigo-600">
                        {{ $order->order_number }}
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $order->customer_name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $order->customer_email }}
                    </p>

                    @if($order->name_of_products)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $order->name_of_products }}
                        </p>
                    @endif
                </div>

                <!-- Right -->
                <div class="flex items-center gap-6">

                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            RM{{ number_format($order->total_amount, 2) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    <!-- Status -->
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-700' : 
                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($order->status) }}
                    </span>

                    <!-- Delete -->
                    @if($order->status === 'pending' && !$order->invoice)
                    <form action="{{ route('admin.orders.destroy', $order) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this order?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-500 hover:text-red-700 text-sm font-medium">
                            Delete
                        </button>
                    </form>
                    @endif

                </div>

            </div>

            @if($order->invoice)
            <div class="mt-3 text-xs text-gray-500">
                Invoice: {{ $order->invoice->invoice_number }} ({{ $order->invoice->status }})
            </div>
            @endif

        </a>

        @empty
        <div class="text-center py-12">
            <p class="text-gray-400 text-sm">No orders found</p>
            <a href="{{ route('admin.orders.create') }}"
               class="mt-3 inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                Create your first order →
            </a>
        </div>
        @endforelse

    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif

</div>
@endsection
