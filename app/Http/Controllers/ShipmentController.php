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
        
        return view('invotrack.shipments.index', compact('shipments'));
    }

    /**
     * Display the specified shipment.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['invoice.order']);
        
        return view('invotrack.shipments.show', compact('shipment'));
    }

    /**
     * Track shipment with map and location history.
     */
    public function track(Shipment $shipment)
    {
        $shipment->load(['invoice.order', 'invoice.payments']);

        return view('invotrack.shipments.track', compact('shipment'));
    }

    /**
     * Show shipment timeline
     */
    public function timeline(Shipment $shipment)
    {
        $shipment->load(['invoice.order', 'invoice.payments']);
        
        // Create timeline events based on shipment status and dates
        $timeline = $this->generateTimeline($shipment);

        return view('invotrack.shipments.timeline', compact('shipment', 'timeline'));
    }

    /**
     * Generate shipment timeline events
     */
    private function generateTimeline(Shipment $shipment)
    {
        $timeline = [];

        // Booking Confirmed
        $timeline[] = [
            'title' => 'Booking Confirmed',
            'description' => 'Transport booking has been confirmed.',
            'date' => $shipment->created_at,
            'status' => 'completed',
            'icon' => 'shopping-cart'
        ];

        // Lorry Assigned
        if ($shipment->status === 'lorry_assigned' || $shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Lorry Assigned',
                'description' => 'Lorry has been assigned for this transport.',
                'date' => $shipment->created_at,
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Lorry On The Way to Customer (Pickup in progress)
        if ($shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Lorry On The Way to Customer',
                'description' => 'Lorry is traveling to customer location for pickup.',
                'date' => $shipment->pickup_started_at ?? now(),
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Cargo Picked Up
        if ($shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Cargo Picked Up',
                'description' => 'Cargo has been successfully picked up from customer.',
                'date' => $shipment->picked_up_at ?? now(),
                'status' => 'completed',
                'icon' => 'package'
            ];
        }

        // In Transit to Port
        if ($shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'In Transit to Port',
                'description' => 'Lorry is transporting cargo to the destination port.',
                'date' => $shipment->picked_up_at ?? now(),
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Arrived at Port
        if ($shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Arrived at Port',
                'description' => 'Cargo has arrived at the destination port.',
                'date' => $shipment->arrived_at_port_at ?? now(),
                'status' => 'completed',
                'icon' => 'check-circle'
            ];
        }

        // Proof of Arrival Uploaded
        if ($shipment->proof_of_arrival_file_path) {
            $timeline[] = [
                'title' => 'Proof of Arrival Uploaded',
                'description' => 'Proof of cargo pickup has been uploaded.',
                'date' => $shipment->picked_up_at ?? now(),
                'status' => 'completed',
                'icon' => 'file-check'
            ];
        }

        // Proof of Delivery Uploaded
        if ($shipment->pod_file_path) {
            $timeline[] = [
                'title' => 'Proof of Delivery Uploaded',
                'description' => 'Proof of arrival at port has been uploaded.',
                'date' => $shipment->arrived_at_port_at ?? now(),
                'status' => 'completed',
                'icon' => 'file-check'
            ];
        }

        return $timeline;
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

        return view('invotrack.shipments.create', compact('invoice'));
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
            'status' => 'required|in:booking_confirmed,lorry_assigned,en_route_to_pickup,cargo_picked_up,in_transit_to_port,arrived_at_port',
        ]);

        // Check if payment is made before allowing status beyond booking_confirmed
        if ($validated['status'] !== 'booking_confirmed') {
            if (!$shipment->invoice || $shipment->invoice->status !== 'paid') {
                return back()->with('error', 'Customer must make payment before updating shipment status beyond Booking Confirmed.');
            }
        }

        switch ($validated['status']) {
            case 'lorry_assigned':
                $shipment->markLorryAssigned();
                break;
            case 'en_route_to_pickup':
                $shipment->markEnRouteToPickup();
                break;
            case 'cargo_picked_up':
                $shipment->markCargoPickedUp();
                break;
            case 'in_transit_to_port':
                $shipment->markInTransitToPort();
                break;
            case 'arrived_at_port':
                $shipment->markArrivedAtPort();
                break;
            default:
                $shipment->update(['status' => 'booking_confirmed']);
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

    /**
     * Upload proof of arrival (cargo pickup).
     */
    public function uploadProofOfArrival(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'arrival_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('arrival_file')) {
            $file = $request->file('arrival_file');
            $filename = 'arrival_' . $shipment->tracking_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('arrival', $filename, 'public');
            
            $shipment->uploadProofOfArrival($path);
        }

        return back()->with('success', 'Proof of arrival uploaded successfully.');
    }
}
