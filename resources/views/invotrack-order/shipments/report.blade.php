@extends('invotrack-order.layouts.app')

@section('title', 'Shipment Report')

@section('content')
<div class="px-4 py-6 sm:px-0 bg-gray-50 min-h-screen" id="report-container">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Shipment Report</h1>
            <p class="text-sm text-gray-500">Complete shipment summary and transaction details</p>
        </div>
        <a href="{{ route('customer.shipments.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Shipments
        </a>
    </div>

    <!-- Report Content -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Report Header -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900">INVOTRACK - TRANSACTION REPORT</h2>
                <p class="text-sm text-gray-500 mt-1">Generated on: {{ now()->format('F d, Y - H:i') }}</p>
            </div>

            <!-- Order Details -->
            <div class="report-section mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-indigo-600 rounded mr-3"></span>
                    ORDER DETAILS
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Order Number:</span>
                        <span class="text-gray-900 font-semibold">{{ $shipment->invoice->order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Customer Name:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Customer Email:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Customer Phone:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->customer_phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Delivery Address:</span>
                        <span class="text-gray-900 max-w-md text-right">{{ $shipment->invoice->order->customer_address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Products:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->name_of_products }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Quantity:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->quantity }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Transportation Type:</span>
                        <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $shipment->invoice->order->transportation_type)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Delivery Destination:</span>
                        <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $shipment->invoice->order->delivery_destination)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Cargo Size:</span>
                        <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $shipment->invoice->order->cargo_size)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Type of Goods:</span>
                        <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $shipment->invoice->order->type_of_goods)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Order Status:</span>
                        <span class="text-gray-900 font-semibold">{{ ucfirst($shipment->invoice->order->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Order Created:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->order->created_at->format('F d, Y - H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="report-section mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-green-600 rounded mr-3"></span>
                    INVOICE DETAILS
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Number:</span>
                        <span class="text-gray-900 font-semibold">{{ $shipment->invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Amount:</span>
                        <span class="text-gray-900 font-semibold">RM{{ number_format($shipment->invoice->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Status:</span>
                        <span class="text-gray-900 font-semibold">{{ ucfirst($shipment->invoice->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Created:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->created_at->format('F d, Y - H:i') }}</span>
                    </div>
                    @if($shipment->invoice->issued_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Issued:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->issued_date->format('F d, Y') }}</span>
                    </div>
                    @endif
                    @if($shipment->invoice->paid_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Invoice Paid:</span>
                        <span class="text-gray-900">{{ $shipment->invoice->paid_date->format('F d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Details -->
            <div class="report-section mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-blue-600 rounded mr-3"></span>
                    PAYMENT DETAILS
                </h3>
                @if($shipment->invoice->payments->count() > 0)
                    @foreach($shipment->invoice->payments as $payment)
                    <div class="bg-gray-50 rounded-lg p-6 space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Payment Amount:</span>
                            <span class="text-gray-900 font-semibold">RM{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Payment Method:</span>
                            <span class="text-gray-900">{{ ucfirst($payment->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Payment Status:</span>
                            <span class="text-gray-900 font-semibold">{{ ucfirst($payment->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Payment Created:</span>
                            <span class="text-gray-900">{{ $payment->created_at->format('F d, Y - H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Payment Date:</span>
                            <span class="text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</span>
                        </div>
                        @if($payment->transaction_reference)
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Transaction Reference:</span>
                            <span class="text-gray-900">{{ $payment->transaction_reference }}</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-gray-500">
                        No payments recorded for this invoice.
                    </div>
                @endif
            </div>

            <!-- Shipment Details -->
            <div class="report-section mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-purple-600 rounded mr-3"></span>
                    SHIPMENT DETAILS
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Tracking Number:</span>
                        <span class="text-gray-900 font-semibold">{{ $shipment->tracking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Courier Name:</span>
                        <span class="text-gray-900">{{ $shipment->courier_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Shipping Address:</span>
                        <span class="text-gray-900 max-w-md text-right">{{ $shipment->shipping_address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Shipment Status:</span>
                        <span class="text-gray-900 font-semibold">{{ ucfirst(str_replace('_', ' ', $shipment->status)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Shipment Created:</span>
                        <span class="text-gray-900">{{ $shipment->created_at->format('F d, Y - H:i') }}</span>
                    </div>
                    @if($shipment->pickup_started_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Lorry En Route to Pickup:</span>
                        <span class="text-gray-900">{{ $shipment->pickup_started_at->format('F d, Y - H:i') }}</span>
                    </div>
                    @endif
                    @if($shipment->picked_up_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Cargo Collected from Customer:</span>
                        <span class="text-gray-900">{{ $shipment->picked_up_at->format('F d, Y - H:i') }}</span>
                    </div>
                    @endif
                    @if($shipment->arrived_at_port_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Arrived at Port:</span>
                        <span class="text-gray-900 font-semibold text-green-600">{{ $shipment->arrived_at_port_at->format('F d, Y - H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Proof Documents -->
            <div class="report-section mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-orange-600 rounded mr-3"></span>
                    PROOF DOCUMENTS
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                    @if($shipment->proof_of_arrival_file_path)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Proof of Arrival:</span>
                        <span class="text-gray-900">Uploaded</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Proof of Arrival:</span>
                        <span class="text-gray-500">Not uploaded</span>
                    </div>
                    @endif
                    @if($shipment->pod_file_path)
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Proof of Delivery (POD):</span>
                        <span class="text-gray-900">Uploaded</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Proof of Delivery (POD):</span>
                        <span class="text-gray-500">Not uploaded</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="report-section border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-red-600 rounded mr-3"></span>
                    TRANSACTION SUMMARY
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Payment Status:</span>
                        <span class="text-gray-900 font-semibold">{{ $shipment->invoice->status === 'closed' ? 'Paid & Closed' : ucfirst($shipment->invoice->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Transaction Status:</span>
                        <span class="text-gray-900 font-bold text-green-600">
                            @if($shipment->status === 'arrived_at_port' && $shipment->invoice->status === 'closed')
                                COMPLETED
                            @elseif($shipment->status === 'arrived_at_port')
                                SHIPMENT COMPLETED
                            @elseif($shipment->invoice->status === 'closed')
                                PAYMENT COMPLETED
                            @else
                                IN PROGRESS
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Print Button -->
            <div class="mt-8 flex justify-end">
                <button onclick="window.print()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                    Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide everything except the report content */
        body * {
            visibility: hidden;
        }

        /* Show only the report container and its children */
        #report-container, #report-container * {
            visibility: visible;
        }

        /* Position the report at the top */
        #report-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Hide the page header section inside report container */
        #report-container > .flex.justify-between.items-center.mb-6 {
            display: none !important;
        }

        /* Prevent page breaks inside sections */
        .report-section {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        /* Prevent headings from being separated from content */
        .report-section h3 {
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Allow page breaks between sections */
        .report-section {
            page-break-after: auto;
            break-after: auto;
        }

        /* Ensure container doesn't break */
        .bg-white {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Hide non-print elements - navigation, header, buttons */
        button, nav, header, a[href*="customer.shipments.index"],
        header.bg-white, header.sticky, .container.mx-auto, .flex.items-center.justify-between {
            display: none !important;
        }

        /* Hide the entire header section */
        header {
            display: none !important;
        }

        /* Adjust margins for print */
        body {
            margin: 0;
            padding: 0;
        }

        .px-4, .p-8 {
            padding: 0 !important;
        }

        /* Ensure text is readable */
        .text-gray-900, .text-gray-600, .text-gray-500 {
            color: #000 !important;
        }

        /* Remove background colors for cleaner print */
        .bg-gray-50, .bg-white {
            background-color: #fff !important;
        }

        /* Keep borders for structure */
        .border-b, .border-gray-200 {
            border-bottom: 1px solid #000 !important;
        }

        /* Ensure colored indicators print correctly */
        .bg-indigo-600, .bg-green-600, .bg-blue-600, .bg-purple-600, .bg-orange-600, .bg-red-600 {
            background-color: #000 !important;
        }
    }
</style>
@endsection
