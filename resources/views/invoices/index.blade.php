@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                    <li>
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600">
                                                {{ $invoice->invoice_number }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Order: {{ $invoice->order->order_number }} - {{ $invoice->order->customer_name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                RM{{ number_format($invoice->amount, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $invoice->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $invoice->status }}
                                        </span>
                                        <!-- Delete Button - Only for draft invoices without payments -->
                                        @if($invoice->status === 'draft' && $invoice->payments->count() === 0)
                                        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                        @endif
                                        @if($invoice->status === 'paid' && !$invoice->shipment)
                                            <a href="{{ route('admin.shipments.create', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                Create Shipment
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Workflow Progress -->
                                <div class="mt-2">
                                    <div class="flex items-center space-x-1">
                                        <div class="workflow-step {{ $invoice->getWorkflowStatus()['order'] === 'completed' ? 'completed' : 'pending' }}">1</div>
                                        <div class="workflow-step {{ $invoice->getWorkflowStatus()['invoice'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['invoice'] === 'in_progress' ? 'in_progress' : 'pending') }}">2</div>
                                        <div class="workflow-step {{ $invoice->getWorkflowStatus()['payment'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['payment'] === 'in_progress' ? 'in_progress' : 'pending') }}">3</div>
                                        <div class="workflow-step {{ $invoice->getWorkflowStatus()['shipment'] === 'completed' ? 'completed' : ($invoice->getWorkflowStatus()['shipment'] === 'in_progress' ? 'in_progress' : 'pending') }}">4</div>
                                    </div>
                                </div>

                                @if($invoice->payments->count() > 0)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">
                                            Payments: RM{{ number_format($invoice->payments->where('status', 'verified')->sum('amount'), 2) }} / RM{{ number_format($invoice->amount, 2) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-gray-500">
                        No invoices found. Invoices are automatically generated when orders are created.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="mt-6">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
