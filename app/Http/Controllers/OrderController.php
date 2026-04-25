<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('invoice');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(10)->withQueryString();

        return view('invotrack.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('invotrack.orders.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20|regex:/^[+]?[0-9\s\-()]+$/',
            'customer_address' => 'required|string|min:10|max:500',
            'total_amount' => 'required|numeric|min:0.01|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'Please provide a valid email address.',
            'customer_phone.regex' => 'Please provide a valid phone number.',
            'customer_address.required' => 'Customer address is required.',
            'customer_address.min' => 'Address must be at least 10 characters long.',
            'total_amount.required' => 'Order amount is required.',
            'total_amount.min' => 'Order amount must be greater than 0.',
        ]);

        try {
            DB::beginTransaction();

            $validated['order_number'] = Order::generateOrderNumber();
            $validated['status'] = 'confirmed';

            $order = Order::create($validated);

            // Auto-generate invoice when order is created
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'status' => 'issued', // Auto-issue for better workflow
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
            ]);

            DB::commit();

            NotificationHelper::orderCreated($order->order_number);
            NotificationHelper::invoiceGenerated($invoice->invoice_number);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', "Order #{$order->order_number} created successfully with invoice #{$invoice->invoice_number}");

        } catch (\Exception $e) {
            DB::rollBack();
            NotificationHelper::error('Failed to create order. Please try again.');
            return back()->withInput()
                ->with('error', 'Failed to create order. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('invoice.payments', 'invoice.shipment');
        return view('invotrack.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        return view('invotrack.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $order->update($validated);

        // Update invoice amount if order amount changed
        if ($order->invoice && $order->total_amount != $validated['total_amount']) {
            $order->invoice->update(['amount' => $validated['total_amount']]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Business rule validation: Can only delete pending orders without invoice
        if ($order->status !== 'pending') {
            return back()->with('error', 'Cannot delete order: Order is already ' . $order->status);
        }

        if ($order->invoice) {
            return back()->with('error', 'Cannot delete order: Invoice already generated');
        }

        try {
            $order->delete();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
}
