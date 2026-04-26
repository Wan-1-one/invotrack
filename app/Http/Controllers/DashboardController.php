<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Shipment;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::whereIn('status', ['paid', 'closed'])->count(),
            'pending_shipments' => Shipment::where('status', 'pending')->count(),
            'shipped_count' => Shipment::where('status', 'shipped')->count(),
            'delivered_count' => Shipment::whereIn('status', ['delivered', 'arrived_at_port'])->count(),
            'total_shipments' => Shipment::count(),
        ];

        $recentOrders = Order::with('invoice')->latest()->take(2)->get();
        $recentInvoices = Invoice::with(['order', 'payments', 'shipment'])->latest()->take(2)->get();

        return view('dashboard', compact('stats', 'recentOrders', 'recentInvoices'));
    }
}
