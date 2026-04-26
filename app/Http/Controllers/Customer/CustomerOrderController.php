<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerOrderController extends Controller
{
    /**
     * Show the order creation form
     */
    public function create()
    {
        return view('invotrack-order.orders.create');
    }

    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20', 'regex:/^[+]?[0-9\s\-()]+$/'],
            'customer_address' => ['required', 'string', 'min:10', 'max:500'],
            'quantity' => ['required', 'integer', 'min:1'],
            'name_of_products' => ['required', 'string', 'max:255'],
            'auto_price' => ['required', 'numeric', 'min:0.01'],
            'transportation_type' => ['required', 'string', 'in:container_20ft,container_40ft,box_truck,curtain_sider,flatbed,refrigerated_truck'],
            'delivery_destination' => ['required', 'string', 'in:port_klang,westports_port_klang,northport_port_klang,tanjung_pelepas,johor_port,penang_port,kuantan_port'],
            'cargo_size' => ['required', 'string', 'in:small,medium,large,fcl'],
            'type_of_goods' => ['required', 'string', 'in:furniture,electronics,frozen_food,construction_materials,machinery,vehicles,textiles,chemicals,paper_products,plastic_products,metal_products,agricultural_products,medical_supplies,general_cargo'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'Please provide a valid email address.',
            'customer_phone.regex' => 'Please provide a valid phone number.',
            'customer_address.required' => 'Customer address is required.',
            'customer_address.min' => 'Address must be at least 10 characters long.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
            'name_of_products.required' => 'Product name is required.',
            'auto_price.required' => 'Price is required.',
            'auto_price.min' => 'Price must be greater than 0.',
            'transportation_type.required' => 'Transportation type is required.',
            'delivery_destination.required' => 'Delivery destination is required.',
            'cargo_size.required' => 'Cargo size is required.',
            'type_of_goods.required' => 'Type of goods is required.',
        ]);

        $customer = Auth::user();

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

            // Store customer info in session for non-authenticated users
            if (!$customer) {
                session(['customer_phone' => $request->customer_phone]);
            }

            // Create the order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer?->id,
                'customer_name' => $customer?->name ?? $request->customer_name,
                'customer_email' => $customer?->email ?? $request->customer_email,
                'customer_phone' => $customer?->profile?->phone ?? $request->customer_phone,
                'customer_address' => $customer?->profile?->address ?? $request->customer_address,
                'total_amount' => $totalAmount,
                'quantity' => $request->quantity,
                'price_per_unit' => $request->auto_price,
                'name_of_products' => $request->name_of_products,
                'transportation_type' => $request->transportation_type,
                'delivery_destination' => $request->delivery_destination,
                'cargo_size' => $request->cargo_size,
                'type_of_goods' => $request->type_of_goods,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Auto-generate invoice with proper number generation
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'status' => 'issued',
                'issued_date' => now(),
            ]);

            // Auto-create shipment for logistics orders
            $shipment = Shipment::create([
                'invoice_id' => $invoice->id,
                'tracking_number' => Shipment::generateTrackingNumber(),
                'shipping_address' => $customer?->profile?->address ?? $request->customer_address,
                'status' => 'booking_confirmed',
                'courier_name' => 'Pending Assignment',
            ]);

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order created successfully! Invoice and shipment have been generated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create order. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Ensure customer can only view their own orders
        if (Auth::check() && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $order->load(['invoice', 'invoice.shipment', 'invoice.payments']);

        return view('invotrack-order.orders.show', compact('order'));
    }

    /**
     * Show the order editing form
     */
    public function edit(Order $order)
    {
        // Ensure customer can only edit their own orders
        if (Auth::check() && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('invotrack-order.orders.edit', compact('order'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        // Ensure customer can only update their own orders
        if (Auth::check() && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'customer_address' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Cancel the specified order
     */
    public function cancel(Order $order)
    {
        // Ensure customer can only cancel their own orders
        if (Auth::check() && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Only allow cancellation of pending orders without paid invoices
        if ($order->status !== 'pending' || ($order->invoice && $order->invoice->status === 'paid')) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order cancelled successfully!');
    }

    /**
     * Display a listing of the customer's orders
     */
    public function index()
    {
        // Get all orders since there's no authentication
        $orders = Order::with('invoice')->orderBy('created_at', 'desc')->paginate(10);

        return view('invotrack-order.orders.index', compact('orders'));
    }
}
