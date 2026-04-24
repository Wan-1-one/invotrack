<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Invoice;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        $invoice = $payment->invoice;
        
        if ($payment->status === 'verified') {
            // Check if invoice is now fully paid
            $totalVerified = $invoice->payments()->where('status', 'verified')->sum('amount');
            
            if ($totalVerified >= $invoice->amount && $invoice->status !== 'paid') {
                $invoice->update([
                    'status' => 'paid',
                    'paid_date' => now(),
                ]);
            }
        }
        
        if ($payment->status === 'failed') {
            // You might want to notify customer about payment failure
            // This could trigger an email or notification
        }
    }

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        // Update invoice status to indicate payment is pending
        $invoice = $payment->invoice;
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'issued']);
        }
    }
}
