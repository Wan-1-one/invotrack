@extends('customer.layout')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
        <p class="mt-1 text-gray-500">Overview of your logistics activity</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        @php
            $cards = [
                ['title'=>'Total Orders','value'=>$stats['total_orders'],'color'=>'purple'],
                ['title'=>'Pending Payment','value'=>$stats['pending_payment'],'color'=>'yellow'],
                ['title'=>'Confirmed Orders','value'=>$stats['confirmed_orders'],'color'=>'green'],
                ['title'=>'Total Spent','value'=>'RM'.number_format($stats['total_amount'],2),'color'=>'blue'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="rounded-xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden
            {{ $card['color'] === 'purple' ? 'bg-gradient-to-br from-purple-500 to-purple-700 text-white' :
               ($card['color'] === 'yellow' ? 'bg-gradient-to-br from-yellow-400 to-orange-500 text-white' :
               ($card['color'] === 'green' ? 'bg-gradient-to-br from-green-500 to-emerald-600 text-white' :
               'bg-gradient-to-br from-blue-500 to-blue-700 text-white')) }}">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        @if($card['color'] === 'purple')
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        @elseif($card['color'] === 'yellow')
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($card['color'] === 'green')
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-sm opacity-90">{{ $card['title'] }}</p>
                <p class="mt-2 text-2xl font-semibold">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach

    </div>

    <!-- Main Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Recent Orders -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="{{ route('customer.orders.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                    View all
                </a>
            </div>

            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                        <div class="py-4 flex justify-between items-center hover:bg-gray-50 px-2 rounded-lg transition">
                            
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>

                            <div class="text-right">
                                <p class="font-semibold text-gray-900">
                                    RM{{ number_format($order->total_amount, 2) }}
                                </p>

                                <span class="mt-1 inline-block px-3 py-1 text-xs font-medium rounded-full
                                    {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-700' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-sm">No orders yet</p>
                        <a href="{{ route('customer.orders.create') }}"
                           class="mt-3 inline-block text-purple-600 hover:text-purple-800 font-medium">
                            Place your first order →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Active Shipments -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Active Shipments</h3>
                <a href="{{ route('customer.shipments.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                    View all
                </a>
            </div>

            <div class="p-6">
                @if($activeShipments->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($activeShipments as $shipment)
                        <div class="py-4 flex justify-between items-center hover:bg-gray-50 px-2 rounded-lg transition">

                            <div>
                                <p class="font-medium text-gray-900">{{ $shipment->tracking_number }}</p>
                                <p class="text-sm text-gray-500">{{ $shipment->courier_name }}</p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                </p>

                                <a href="{{ route('customer.shipments.track', $shipment) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Track →
                                </a>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-sm">No active shipments</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
