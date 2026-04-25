<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Show the customer dashboard
     */
    public function index()
    {
        $customer = Auth::user();
        
        // Get recent orders
        $recentOrders = $customer ? 
            $customer->orders()->orderBy('created_at', 'desc')->take(5)->get() :
            collect();

        // Get order statistics
        $stats = [
            'total_orders' => $customer ? $customer->orders()->count() : 0,
            'pending_orders' => $customer ? $customer->orders()->where('status', 'pending')->count() : 0,
            'confirmed_orders' => $customer ? $customer->orders()->where('status', 'confirmed')->count() : 0,
            'total_amount' => $customer ? $customer->orders()->sum('total_amount') : 0,
        ];

        // Get recent invoices
        $recentInvoices = $customer ? 
            Invoice::whereHas('order', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->orderBy('created_at', 'desc')->take(3)->get() :
            collect();

        // Get active shipments
        $activeShipments = $customer ? 
            Shipment::whereHas('invoice.order', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->where('status', '!=', 'delivered')->orderBy('created_at', 'desc')->take(3)->get() :
            collect();

        return view('customer.dashboard', compact('recentOrders', 'stats', 'recentInvoices', 'activeShipments'));
    }
}
