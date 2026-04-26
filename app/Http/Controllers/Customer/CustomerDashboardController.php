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
        // Get all orders since there's no authentication
        $customerOrders = Order::orderBy('created_at', 'desc');

        // Get recent orders (top 2)
        $recentOrders = $customerOrders->take(2)->get();

        // Get order statistics
        $stats = [
            'total_orders' => $customerOrders->count(),
            'pending_payment' => Invoice::whereNotIn('status', ['paid', 'closed'])->count(),
            'confirmed_orders' => (clone $customerOrders)->where('status', 'confirmed')->count(),
            'total_amount' => (clone $customerOrders)->sum('total_amount'),
        ];

        // Get active shipments (top 2) - only show if lorry assigned or payment made
        $activeShipments = Shipment::where('status', '!=', 'delivered')
            ->where(function($query) {
                $query->where('status', 'lorry_assigned')
                      ->orWhereHas('invoice', function($q) {
                          $q->whereHas('payments');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('customer.dashboard', compact('recentOrders', 'stats', 'activeShipments'));
    }
}
