<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'status',
        'payment_date',
        'transaction_reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Mark payment as verified and update invoice status
     */
    public function verify()
    {
        $this->status = 'verified';
        $this->save();

        $invoice = $this->invoice;
        if ($invoice->isFullyPaid()) {
            $invoice->status = 'paid';
            $invoice->paid_date = now();
            $invoice->save();
        }
    }
}
