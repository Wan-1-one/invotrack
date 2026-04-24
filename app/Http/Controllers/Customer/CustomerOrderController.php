<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Show order creation form
     */
    public function create()
    {
        return view('customer.orders.create');
    }

    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'customer_address' => ['required', 'string', 'max:500'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_unit' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = Auth::user();

        try {
            DB::beginTransaction();
            $totalAmount = $request->quantity * $request->price_per_unit;

            // Store customer info in session for non-authenticated users
            if (!$customer) {
                session(['customer_phone' => $request->customer_phone]);
            }

            // Create the order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer?->id,
                'customer_name' => $customer?->name ?? $request->customer_name,
                'customer_email' => $customer?->email ?? 'no-email@example.com',
                'customer_phone' => $customer?->profile?->phone ?? $request->customer_phone,
                'customer_address' => $customer?->profile?->address ?? $request->customer_address,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Auto-generate invoice with proper number generation
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'status' => 'issued', // Auto-issue the invoice
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
            ]);

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order placed successfully! Invoice #' . $invoice->invoice_number . ' has been generated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to place order. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $customer = Auth::user();
        
        // Access control for authenticated users
        if ($customer) {
            // Check if order belongs to authenticated user by ID or email
            if ($order->customer_id !== $customer->id && 
                ($order->customer_email === null || $order->customer_email !== $customer->email)) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // Access control for non-authenticated users - check session
            $customerPhone = session('customer_phone');
            
            if ($order->customer_phone !== $customerPhone) {
                abort(403, 'Unauthorized access - Please provide your phone to view orders');
            }
        }

        $order->load(['invoice', 'invoice.payments', 'invoice.shipment']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * List customer's orders
     */
    public function index(Request $request)
    {
        $customer = Auth::user();
        
        if ($customer) {
            // Authenticated user - get their orders (avoid duplicates)
            $orders = Order::where(function($query) use ($customer) {
                    $query->where('customer_id', $customer->id)
                          ->orWhere(function($subQuery) use ($customer) {
                              $subQuery->where('customer_email', $customer->email)
                                       ->whereNotNull('customer_email');
                          });
                })
                ->distinct()
                ->with(['invoice', 'invoice.shipment'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Non-authenticated - filter by email/phone from session/request
            $customerEmail = session('customer_email') ?? $request->get('email');
            $customerPhone = session('customer_phone') ?? $request->get('phone');
            
            $query = Order::with(['invoice', 'invoice.shipment'])
                ->orderBy('created_at', 'desc');
                
            if ($customerEmail) {
                $query->where('customer_email', $customerEmail);
            } elseif ($customerPhone) {
                $query->where('customer_phone', $customerPhone);
            } else {
                // No identifier provided - return empty
                $orders = collect();
                return view('customer.orders.index', compact('orders'));
            }
            
            $orders = $query->paginate(10);
        }

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Cancel the specified order (customer limited deletion).
     */
    public function cancel(Order $order)
    {
        $customer = Auth::user();
        
        // Access control: Only cancel own orders
        if ($customer && $order->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access');
        }

        // Business rule validation: Can only cancel pending orders without invoice
        if ($order->status !== 'pending') {
            return back()->with('error', 'Cannot cancel order: Order is already ' . $order->status);
        }

        if ($order->invoice) {
            return back()->with('error', 'Cannot cancel order: Invoice already generated');
        }

        try {
            // Use status update instead of delete for audit trail
            $order->update(['status' => 'cancelled']);
            return redirect()->route('customer.orders.index')
                ->with('success', 'Order cancelled successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}
