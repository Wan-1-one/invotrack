<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerShipmentController extends Controller
{
    /**
     * Show shipment tracking page
     */
    public function track(Shipment $shipment)
    {
        $shipment->load(['invoice.order', 'invoice.payments']);

        return view('invotrack-order.shipments.track', compact('shipment'));
    }

    /**
     * List customer's shipments
     */
    public function index()
    {
        $shipments = \App\Models\Shipment::with(['invoice.order', 'invoice.payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invotrack-order.shipments.index', compact('shipments'));
    }

    /**
     * Show shipment timeline
     */
    public function timeline(Shipment $shipment)
    {
        // Create timeline events based on shipment status and dates
        $timeline = $this->generateTimeline($shipment);

        return view('invotrack-order.shipments.timeline', compact('shipment', 'timeline'));
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
            'description' => 'Your transport booking has been confirmed.',
            'date' => $shipment->created_at,
            'status' => 'completed',
            'icon' => 'shopping-cart'
        ];

        // Lorry Assigned
        if ($shipment->status === 'lorry_assigned' || $shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Lorry Assigned',
                'description' => 'A lorry has been assigned for your transport.',
                'date' => $shipment->created_at,
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Lorry On The Way to Customer (Pickup in progress)
        if ($shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Lorry On The Way to You',
                'description' => 'Lorry is traveling to your location for pickup.',
                'date' => $shipment->pickup_started_at ?? now(),
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Cargo Picked Up
        if ($shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Cargo Picked Up',
                'description' => 'Your cargo has been successfully picked up.',
                'date' => $shipment->picked_up_at ?? now(),
                'status' => 'completed',
                'icon' => 'package'
            ];
        }

        // In Transit to Port
        if ($shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'In Transit to Port',
                'description' => 'Lorry is transporting your cargo to the destination port.',
                'date' => $shipment->picked_up_at ?? now(),
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Arrived at Port
        if ($shipment->status === 'arrived_at_port') {
            $timeline[] = [
                'title' => 'Arrived at Port',
                'description' => 'Your cargo has arrived at the destination port.',
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
     * Generate shipment report
     */
    public function report(Shipment $shipment)
    {
        // Only allow report for arrived_at_port or closed shipments
        if (!in_array($shipment->status, ['arrived_at_port']) && (!$shipment->invoice || $shipment->invoice->status !== 'closed')) {
            return back()->with('error', 'Report can only be generated for shipments that have arrived at port or closed transactions.');
        }

        $shipment->load(['invoice.order', 'invoice.payments']);

        return view('invotrack-order.shipments.report', compact('shipment'));
    }
}
