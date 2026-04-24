<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'tracking_number',
        'status',
        'shipped_date',
        'delivered_date',
        'courier_name',
        'shipping_address',
        'pod_file_path',
        'notes',
    ];

    protected $casts = [
        'shipped_date' => 'date',
        'delivered_date' => 'date',
    ];

    /**
     * Get the invoice that owns the shipment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Generate unique tracking number
     */
    public static function generateTrackingNumber()
    {
        $prefix = 'TRK';
        $year = date('Y');
        $lastShipment = self::where('tracking_number', 'like', "{$prefix}{$year}%")
            ->orderBy('tracking_number', 'desc')
            ->first();
        
        $sequence = $lastShipment ? (int)substr($lastShipment->tracking_number, -6) + 1 : 1;
        return "{$prefix}{$year}" . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Mark as shipped
     */
    public function markAsShipped()
    {
        $this->status = 'shipped';
        $this->shipped_date = now();
        $this->save();
    }

    /**
     * Mark as delivered and close the transaction
     */
    public function markAsDelivered()
    {
        $this->status = 'delivered';
        $this->delivered_date = now();
        $this->save();

        // Close the invoice transaction
        $invoice = $this->invoice;
        $invoice->status = 'closed';
        $invoice->save();
    }

    /**
     * Upload proof of delivery
     */
    public function uploadPOD($filePath)
    {
        $this->pod_file_path = $filePath;
        $this->save();
    }
}
