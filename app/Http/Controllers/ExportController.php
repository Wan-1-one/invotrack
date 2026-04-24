<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    /**
     * Export orders to CSV
     */
    public function exportOrdersCSV(Request $request)
    {
        $query = Order::with(['invoice', 'invoice.payments', 'invoice.shipment']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = "orders_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Customer Phone',
                'Customer Address',
                'Total Amount',
                'Status',
                'Invoice Number',
                'Invoice Status',
                'Payment Status',
                'Shipment Status',
                'Created At',
                'Updated At'
            ]);

            // CSV Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->customer_address,
                    $order->total_amount,
                    $order->status,
                    $order->invoice?->invoice_number ?? 'N/A',
                    $order->invoice?->status ?? 'N/A',
                    $order->invoice?->payments->where('status', 'verified')->sum('amount') >= $order->invoice?->amount ? 'Paid' : 'Pending',
                    $order->invoice?->shipment?->status ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export invoices to CSV
     */
    public function exportInvoicesCSV(Request $request)
    {
        $query = Invoice::with(['order', 'payments', 'shipment']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->get();

        $filename = "invoices_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Invoice Number',
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Amount',
                'Status',
                'Issue Date',
                'Due Date',
                'Paid Date',
                'Total Paid',
                'Payment Status',
                'Shipment Status',
                'Created At'
            ]);

            // CSV Data
            foreach ($invoices as $invoice) {
                $totalPaid = $invoice->payments()->where('status', 'verified')->sum('amount');
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->order?->order_number ?? 'N/A',
                    $invoice->order?->customer_name ?? 'N/A',
                    $invoice->order?->customer_email ?? 'N/A',
                    $invoice->amount,
                    $invoice->status,
                    $invoice->issue_date?->format('Y-m-d') ?? 'N/A',
                    $invoice->due_date?->format('Y-m-d') ?? 'N/A',
                    $invoice->paid_date?->format('Y-m-d') ?? 'N/A',
                    $totalPaid,
                    $totalPaid >= $invoice->amount ? 'Fully Paid' : 'Partial/Unpaid',
                    $invoice->shipment?->status ?? 'Not Shipped',
                    $invoice->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate financial summary report
     */
    public function financialSummary(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_orders' => Order::whereDate('created_at', '>=', $dateFrom)
                                   ->whereDate('created_at', '<=', $dateTo)
                                   ->count(),
            'total_revenue' => Invoice::whereDate('created_at', '>=', $dateFrom)
                                     ->whereDate('created_at', '<=', $dateTo)
                                     ->where('status', 'paid')
                                     ->sum('amount'),
            'pending_payments' => Payment::whereDate('created_at', '>=', $dateFrom)
                                        ->whereDate('created_at', '<=', $dateTo)
                                        ->where('status', 'pending')
                                        ->sum('amount'),
            'completed_shipments' => Shipment::whereDate('created_at', '>=', $dateFrom)
                                          ->whereDate('created_at', '<=', $dateTo)
                                          ->where('status', 'delivered')
                                          ->count(),
        ];

        return view('reports.financial_summary', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Print individual invoice
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['order', 'payments', 'shipment']);
        
        return view('invoices.print', compact('invoice'));
    }
}
