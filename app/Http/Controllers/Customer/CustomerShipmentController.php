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
        // Ensure customer can only track their own shipments
        if ($shipment->invoice->order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $shipment->load(['invoice.order', 'invoice.payments']);

        return view('customer.shipments.track', compact('shipment'));
    }

    /**
     * List customer's shipments
     */
    public function index()
    {
        $customer = Auth::user();
        
        $shipments = ($customer ? $customer->orders() : \App\Models\Order::query())
            ->whereHas('invoice.shipment')
            ->with(['invoice.shipment', 'invoice.order'])
            ->get()
            ->map(function ($order) {
                return $order->invoice->shipment;
            })
            ->sortByDesc('created_at');

        return view('customer.shipments.index', compact('shipments'));
    }

    /**
     * Show shipment timeline
     */
    public function timeline(Shipment $shipment)
    {
        // Ensure customer can only view their own shipments
        if ($shipment->invoice->order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Create timeline events based on shipment status and dates
        $timeline = $this->generateTimeline($shipment);

        return view('customer.shipments.timeline', compact('shipment', 'timeline'));
    }

    /**
     * Generate shipment timeline events
     */
    private function generateTimeline(Shipment $shipment)
    {
        $timeline = [];

        // Order placed
        $timeline[] = [
            'title' => 'Order Placed',
            'description' => 'Your order has been received and is being processed.',
            'date' => $shipment->invoice->order->created_at,
            'status' => 'completed',
            'icon' => 'shopping-cart'
        ];

        // Invoice generated
        $timeline[] = [
            'title' => 'Invoice Generated',
            'description' => 'Invoice has been generated for your order.',
            'date' => $shipment->invoice->created_at,
            'status' => 'completed',
            'icon' => 'file-invoice'
        ];

        // Payment confirmed
        if ($shipment->invoice->paid_date) {
            $timeline[] = [
                'title' => 'Payment Confirmed',
                'description' => 'Your payment has been received and confirmed.',
                'date' => $shipment->invoice->paid_date,
                'status' => 'completed',
                'icon' => 'credit-card'
            ];
        }

        // Shipment created
        $timeline[] = [
            'title' => 'Shipment Created',
            'description' => 'Your order has been packaged and is ready for shipment.',
            'date' => $shipment->created_at,
            'status' => 'completed',
            'icon' => 'package'
        ];

        // Shipped
        if ($shipment->shipped_date) {
            $timeline[] = [
                'title' => 'Order Shipped',
                'description' => "Your order has been shipped via {$shipment->courier_name}.",
                'date' => $shipment->shipped_date,
                'status' => 'completed',
                'icon' => 'truck'
            ];
        }

        // Delivered
        if ($shipment->delivered_date) {
            $timeline[] = [
                'title' => 'Order Delivered',
                'description' => 'Your order has been successfully delivered.',
                'date' => $shipment->delivered_date,
                'status' => 'completed',
                'icon' => 'check-circle'
            ];
        }

        return $timeline;
    }
}
