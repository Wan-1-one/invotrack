<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Shipment;

class InvoiceObserver
{
    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Auto-update order status when invoice changes
        $order = $invoice->order;
        if ($order) {
            if ($invoice->status === 'paid' && $order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }
            
            if ($invoice->status === 'closed') {
                $order->update(['status' => 'completed']);
            }
        }

        // Auto-allow shipment when invoice is paid
        if ($invoice->status === 'paid' && !$invoice->shipment) {
            // You might want to create a shipment automatically here
            // or just allow admin to create it
        }
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        // Auto-update order status when invoice is created
        $order = $invoice->order;
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
        }
    }
}
