<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function index()
    {
        $customer = Auth::user();
        
        // Get customer's orders with their relationships (limit to 2 most recent)
        $orders = $customer ? $customer->orders() : \App\Models\Order::query()
            ->with(['invoice', 'invoice.payments', 'invoice.shipment'])
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Calculate statistics
        $stats = [
            'total_orders' => $customer ? $customer->orders()->count() : \App\Models\Order::count(),
            'pending_orders' => $customer ? $customer->orders()->where('status', 'pending')->count() : \App\Models\Order::where('status', 'pending')->count(),
            'confirmed_orders' => $customer ? $customer->orders()->where('status', 'confirmed')->count() : \App\Models\Order::where('status', 'confirmed')->count(),
            'paid_invoices' => $customer ? $customer->invoices()->where('invoices.status', 'paid')->count() : 0,
            'pending_invoices' => $customer ? $customer->invoices()->where('invoices.status', 'issued')->count() : 0,
            'delivered_orders' => $customer ? $customer->orders()
                ->whereHas('invoice.shipment', function($query) {
                    $query->where('status', 'delivered');
                })
                ->count() : 0,
        ];

        return view('customer.dashboard', compact('customer', 'orders', 'stats'));
    }
}
