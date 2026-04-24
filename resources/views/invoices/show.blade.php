@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
                <p class="mt-1 text-sm text-gray-600">Created on {{ $invoice->created_at->format('M d, Y') }}</p>
            </div>
            <div class="flex space-x-3">
                @if($invoice->status === 'draft')
                    <form method="POST" action="{{ route('admin.invoices.issue', $invoice) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Issue Invoice
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Invoice Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Invoice Details</h3>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->invoice_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.orders.show', $invoice->order) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $invoice->order->order_number }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">RM{{ number_format($invoice->amount, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $invoice->status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'Not issued' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Not set' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Paid Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->paid_date ? $invoice->paid_date->format('M d, Y') : 'Not paid' }}</dd>
                            </div>
                        </dl>

                        @if($invoice->notes)
                            <div class="mt-4">
                                <dt class="text-sm font-medium text-gray-500">Invoice Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->notes }}</dd>
                            </div>
                        @endif

                        <!-- Customer Information -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Customer Information</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->order->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->order->customer_email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->order->customer_phone ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->order->customer_address }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Payments -->
                @if($invoice->payments->count() > 0)
                    <div class="bg-white shadow sm:rounded-lg mt-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payment Records</h3>
                            
                            <div class="space-y-4">
                                @foreach($invoice->payments as $payment)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    RM{{ number_format($payment->amount, 2) }} - {{ $payment->payment_method }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Date: {{ $payment->payment_date->format('M d, Y') }}
                                                </p>
                                                @if($payment->transaction_reference)
                                                    <p class="text-sm text-gray-500">
                                                        Reference: {{ $payment->transaction_reference }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $payment->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $payment->status }}
                                            </span>
                                        </div>
                                        @if($payment->notes)
                                            <p class="mt-2 text-sm text-gray-600">{{ $payment->notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Total Paid</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        RM{{ number_format($invoice->payments->where('status', 'verified')->sum('amount'), 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <dt class="text-sm font-medium text-gray-500">Remaining</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        RM{{ number_format($invoice->amount - $invoice->payments->where('status', 'verified')->sum('amount'), 2) }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Shipment Information -->
                @if($invoice->shipment)
                    <div class="bg-white shadow sm:rounded-lg mt-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Shipment Information</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->shipment->tracking_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Courier</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->shipment->courier_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $invoice->shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                               ($invoice->shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $invoice->shipment->status }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Shipped Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->shipment->shipped_date ? $invoice->shipment->shipped_date->format('M d, Y') : 'Not shipped' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Delivered Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->shipment->delivered_date ? $invoice->shipment->delivered_date->format('M d, Y') : 'Not delivered' }}</dd>
                                </div>
                                @if($invoice->shipment->pod_file_path)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Proof of Delivery</dt>
                                        <dd class="mt-1">
                                            <a href="{{ asset('storage/' . $invoice->shipment->pod_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                View POD
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>

                            @if($invoice->shipment->notes)
                                <div class="mt-4">
                                    <dt class="text-sm font-medium text-gray-500">Shipment Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->shipment->notes }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Invoice Summary & Actions -->
            <div>
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Summary & Actions</h3>
                        
                        <!-- Workflow Progress -->
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-700 mb-2">Workflow Progress</p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <div class="workflow-step {{ $invoice->getWorkflowStatus()['order'] === 'completed' ? 'completed' : 'pending' }}">1</div>
                                    <span class="ml-2 text-sm text-gray-600">Order Created</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="workflow-step {{ $invoice->getWorkflowStatus()['invoice'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['invoice'] === 'in_progress' ? 'in_progress' : 'pending') }}">2</div>
                                    <span class="ml-2 text-sm text-gray-600">Invoice Issued</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="workflow-step {{ $invoice->getWorkflowStatus()['payment'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['payment'] === 'in_progress' ? 'in_progress' : 'pending') }}">3</div>
                                    <span class="ml-2 text-sm text-gray-600">Payment Verified</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="workflow-step {{ $invoice->getWorkflowStatus()['shipment'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['shipment'] === 'in_progress' ? 'in_progress' : 'pending') }}">4</div>
                                    <span class="ml-2 text-sm text-gray-600">Shipment Delivered</span>
                                </div>
                            </div>
                        </div>

                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Invoice Amount</dt>
                                <dd class="text-sm font-medium text-gray-900">RM{{ number_format($invoice->amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Paid Amount</dt>
                                <dd class="text-sm text-gray-900">RM{{ number_format($invoice->payments->where('status', 'verified')->sum('amount'), 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Remaining</dt>
                                <dd class="text-sm font-medium text-gray-900">RM{{ number_format($invoice->amount - $invoice->payments->where('status', 'verified')->sum('amount'), 2) }}</dd>
                            </div>
                        </dl>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            @if($invoice->status === 'issued' && !$invoice->isFullyPaid())
                                <a href="{{ route('admin.payments.create', $invoice) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Record Payment
                                </a>
                            @endif

                            @if($invoice->status === 'paid' && !$invoice->shipment)
                                <a href="{{ route('admin.shipments.create', $invoice) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Create Shipment
                                </a>
                            @endif

                            @if($invoice->shipment && $invoice->shipment->status !== 'delivered')
                                <a href="{{ route('admin.shipments.show', $invoice->shipment) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Manage Shipment
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
