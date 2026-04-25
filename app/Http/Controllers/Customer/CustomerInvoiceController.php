<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerInvoiceController extends Controller
{
    /**
     * Display a listing of the customer's invoices
     */
    public function index()
    {
        $customer = Auth::user();
        
        $invoices = $customer ? 
            Invoice::whereHas('order', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->orderBy('created_at', 'desc')->paginate(10) :
            Invoice::orderBy('created_at', 'desc')->paginate(10);

        return view('customer.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        // Ensure customer can only view their own invoices
        if (Auth::check() && $invoice->order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $invoice->load(['order', 'order.customer', 'payments', 'shipment']);

        return view('customer.invoices.show', compact('invoice'));
    }
}
