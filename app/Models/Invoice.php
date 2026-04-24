<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'amount',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the order that owns the invoice.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the shipment for the invoice.
     */
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        $sequence = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -4) + 1 : 1;
        return "{$prefix}{$year}{$month}" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if invoice is fully paid
     */
    public function isFullyPaid()
    {
        return $this->payments()->where('status', 'verified')->sum('amount') >= $this->amount;
    }

    /**
     * Get workflow status
     */
    public function getWorkflowStatus()
    {
        $workflow = [
            'order' => 'completed',
            'invoice' => $this->status === 'draft' ? 'pending' : 'completed',
            'payment' => $this->isFullyPaid() ? 'completed' : 'pending',
            'shipment' => $this->shipment ? ($this->shipment->status === 'delivered' ? 'completed' : 'in_progress') : 'pending',
        ];

        if ($this->status === 'closed') {
            $workflow = array_fill_keys(array_keys($workflow), 'completed');
        }

        return $workflow;
    }
}
