<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the shipments.
     */
    public function index()
    {
        $shipments = Shipment::with(['invoice.order'])
            ->latest()
            ->paginate(10);
        
        return view('shipments.index', compact('shipments'));
    }

    /**
     * Display the specified shipment.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['invoice.order']);
        
        return view('shipments.show', compact('shipment'));
    }

    /**
     * Track shipment with map and location history.
     */
    public function track(Shipment $shipment)
    {
        $shipment->load(['invoice.order']);
        
        // Generate mock tracking data based on status
        $trackingHistory = $this->generateTrackingHistory($shipment);
        $currentLocation = $this->getCurrentLocation($shipment);
        
        return view('shipments.track', compact('shipment', 'trackingHistory', 'currentLocation'));
    }

    /**
     * Generate mock tracking history
     */
    private function generateTrackingHistory(Shipment $shipment)
    {
        $history = [];
        $createdDate = $shipment->created_at;
        
        // Order placed
        $history[] = [
            'date' => $createdDate->format('M d, Y H:i'),
            'status' => 'Order Placed',
            'location' => 'Warehouse',
            'description' => 'Order has been received and is being processed',
            'completed' => true
        ];

        // Package ready
        $readyDate = $createdDate->copy()->addHours(2);
        $history[] = [
            'date' => $readyDate->format('M d, Y H:i'),
            'status' => 'Package Ready',
            'location' => 'Processing Center',
            'description' => 'Package has been prepared for shipment',
            'completed' => true
        ];

        // Shipped (if status is shipped or delivered)
        if ($shipment->status === 'shipped' || $shipment->status === 'delivered') {
            $shippedDate = $shipment->shipped_date ?: $createdDate->copy()->addDay();
            $history[] = [
                'date' => $shippedDate->format('M d, Y H:i'),
                'status' => 'In Transit',
                'location' => 'Distribution Hub',
                'description' => 'Package is in transit to destination',
                'completed' => true
            ];

            // Out for delivery (if delivered)
            if ($shipment->status === 'delivered') {
                $deliveryDate = $shipment->delivered_date ?: $createdDate->copy()->addDays(2);
                $history[] = [
                    'date' => $deliveryDate->copy()->subHours(4)->format('M d, Y H:i'),
                    'status' => 'Out for Delivery',
                    'location' => 'Local Facility',
                    'description' => 'Package is out for delivery',
                    'completed' => true
                ];

                // Delivered
                $history[] = [
                    'date' => $deliveryDate->format('M d, Y H:i'),
                    'status' => 'Delivered',
                    'location' => 'Destination',
                    'description' => 'Package has been delivered successfully',
                    'completed' => true
                ];
            } else {
                // Current step - in transit
                $history[] = [
                    'date' => 'In Progress',
                    'status' => 'In Transit',
                    'location' => 'Distribution Hub',
                    'description' => 'Package is currently in transit',
                    'completed' => false
                ];
            }
        } else {
            // Current step - pending
            $history[] = [
                'date' => 'Pending',
                'status' => 'Awaiting Shipment',
                'location' => 'Processing Center',
                'description' => 'Package is awaiting shipment',
                'completed' => false
            ];
        }

        return $history;
    }

    /**
     * Get current location based on shipment status
     */
    private function getCurrentLocation(Shipment $shipment)
    {
        switch ($shipment->status) {
            case 'pending':
                return [
                    'city' => 'Processing Center',
                    'state' => 'Kuala Lumpur',
                    'coordinates' => [3.1390, 101.6869],
                    'estimated_delivery' => $shipment->created_at->copy()->addDays(3)->format('M d, Y')
                ];
            case 'shipped':
                return [
                    'city' => 'Distribution Hub',
                    'state' => 'Selangor',
                    'coordinates' => [3.0733, 101.5183],
                    'estimated_delivery' => $shipment->created_at->copy()->addDays(2)->format('M d, Y')
                ];
            case 'delivered':
                return [
                    'city' => 'Destination',
                    'state' => 'Delivered',
                    'coordinates' => [3.1390, 101.6869],
                    'estimated_delivery' => 'Delivered'
                ];
            default:
                return [
                    'city' => 'Unknown',
                    'state' => 'Processing',
                    'coordinates' => [3.1390, 101.6869],
                    'estimated_delivery' => 'TBD'
                ];
        }
    }

    /**
     * Show the form for creating a new shipment.
     */
    public function create(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return back()->with('error', 'Shipments can only be created for paid invoices.');
        }

        if ($invoice->shipment) {
            return back()->with('error', 'A shipment already exists for this invoice.');
        }

        return view('shipments.create', compact('invoice'));
    }

    /**
     * Store a newly created shipment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'courier_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        
        if ($invoice->shipment) {
            return back()->with('error', 'A shipment already exists for this invoice.');
        }
        
        $shipment = Shipment::create([
            'invoice_id' => $validated['invoice_id'],
            'tracking_number' => Shipment::generateTrackingNumber(),
            'courier_name' => $validated['courier_name'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Shipment created successfully with tracking number: ' . $shipment->tracking_number);
    }

    /**
     * Update shipment status.
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        if ($validated['status'] === 'shipped') {
            $shipment->markAsShipped();
        } elseif ($validated['status'] === 'delivered') {
            $shipment->markAsDelivered();
        } else {
            $shipment->update(['status' => 'pending']);
        }

        return back()->with('success', 'Shipment status updated successfully.');
    }

    /**
     * Upload proof of delivery.
     */
    public function uploadPOD(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'pod_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('pod_file')) {
            $file = $request->file('pod_file');
            $filename = 'pod_' . $shipment->tracking_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pod', $filename, 'public');
            
            $shipment->uploadPOD($path);
        }

        return back()->with('success', 'Proof of delivery uploaded successfully.');
    }
}
