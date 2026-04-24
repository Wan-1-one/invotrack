<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerInvoiceController extends Controller
{
    /**
     * Show invoice details
     */
    public function show(Invoice $invoice)
    {
        $customer = Auth::user();
        
        // Access control for authenticated users
        if ($customer) {
            if ($invoice->order->customer_id !== $customer->id && $invoice->order->customer_email !== $customer->email) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // Access control for non-authenticated users - check session
            $customerEmail = session('customer_email');
            $customerPhone = session('customer_phone');
            
            if ($invoice->order->customer_email !== $customerEmail && $invoice->order->customer_phone !== $customerPhone) {
                abort(403, 'Unauthorized access - Please provide your email or phone to view invoices');
            }
        }

        $invoice->load(['order', 'payments', 'shipment']);

        return view('customer.invoices.show', compact('invoice'));
    }

    /**
     * List customer's invoices
     */
    public function index(Request $request)
    {
        $customer = Auth::user();
        
        if ($customer) {
            // Authenticated user - get their invoices
            $invoices = Invoice::whereHas('order', function($query) use ($customer) {
                $query->where('customer_id', $customer->id)
                      ->orWhere('customer_email', $customer->email);
            })
            ->with(['order', 'payments', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        } else {
            // Non-authenticated - filter by email/phone from session/request
            $customerEmail = session('customer_email') ?? $request->get('email');
            $customerPhone = session('customer_phone') ?? $request->get('phone');
            
            $query = Invoice::with(['order', 'payments', 'shipment'])
                ->orderBy('created_at', 'desc');
                
            if ($customerEmail) {
                $query->whereHas('order', function($q) use ($customerEmail) {
                    $q->where('customer_email', $customerEmail);
                });
            } elseif ($customerPhone) {
                $query->whereHas('order', function($q) use ($customerPhone) {
                    $q->where('customer_phone', $customerPhone);
                });
            } else {
                // No identifier provided - return empty
                $invoices = collect();
                return view('customer.invoices.index', compact('invoices'));
            }
            
            $invoices = $query->paginate(10);
        }

        return view('customer.invoices.index', compact('invoices'));
    }
}
