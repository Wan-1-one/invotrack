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
            'quantity' => 'required|integer|min:1',
            'name_of_products' => 'required|string|max:255',
            'auto_price' => 'required|numeric|min:0.01',
            'transportation_type' => 'required|string|in:container_20ft,container_40ft,box_truck,curtain_sider,flatbed,refrigerated_truck',
            'delivery_destination' => 'required|string|in:port_klang,westports_port_klang,northport_port_klang,tanjung_pelepas,johor_port,penang_port,kuantan_port',
            'cargo_size' => 'required|string|in:small,medium,large,fcl',
            'type_of_goods' => 'required|string|in:furniture,electronics,frozen_food,construction_materials,machinery,vehicles,textiles,chemicals,paper_products,plastic_products,metal_products,agricultural_products,medical_supplies,general_cargo',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'Please provide a valid email address.',
            'customer_phone.regex' => 'Please provide a valid phone number.',
            'customer_address.required' => 'Customer address is required.',
            'customer_address.min' => 'Address must be at least 10 characters long.',
            'quantity.required' => 'Quantity is required.',
            'name_of_products.required' => 'Product name is required.',
            'auto_price.required' => 'Transportation price is required.',
            'transportation_type.required' => 'Transportation type is required.',
            'delivery_destination.required' => 'Delivery destination is required.',
            'cargo_size.required' => 'Cargo size is required.',
            'type_of_goods.required' => 'Type of goods is required.',
        ]);

        try {
            DB::beginTransaction();

            // Define port pricing
            $portPrices = [
                'port_klang' => 100,
                'westports_port_klang' => 150,
                'northport_port_klang' => 120,
                'tanjung_pelepas' => 200,
                'johor_port' => 180,
                'penang_port' => 160,
                'kuantan_port' => 140
            ];

            $portFee = $portPrices[$request->delivery_destination] ?? 0;
            $totalAmount = $request->quantity * ($request->auto_price + $portFee);

            $validated['order_number'] = Order::generateOrderNumber();
            $validated['customer_id'] = null;
            $validated['total_amount'] = $totalAmount;
            $validated['price_per_unit'] = $request->auto_price + $portFee;
            $validated['status'] = 'confirmed';

            $order = Order::create($validated);

            // Auto-generate invoice when order is created
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'status' => 'issued',
                'issued_date' => now(),
            ]);

            // Auto-create shipment for logistics orders
            $couriers = ['Zaman', 'Omar', 'Kamarul', 'Faiz'];
            $shipment = \App\Models\Shipment::create([
                'invoice_id' => $invoice->id,
                'tracking_number' => \App\Models\Shipment::generateTrackingNumber(),
                'shipping_address' => $request->customer_address,
                'status' => 'booking_confirmed',
                'courier_name' => $couriers[array_rand($couriers)],
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
        $order->load('invoice.payments', 'invoice.shipment', 'document');
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
