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
        'proof_of_arrival_file_path',
        'notes',
        'pickup_started_at',
        'picked_up_at',
        'arrived_at_port_at',
    ];

    protected $casts = [
        'shipped_date' => 'date',
        'delivered_date' => 'date',
        'pickup_started_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'arrived_at_port_at' => 'datetime',
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

    // Logistics-based status methods
    public function markLorryAssigned()
    {
        $this->status = 'lorry_assigned';
        $this->save();
    }

    public function markEnRouteToPickup()
    {
        $this->status = 'en_route_to_pickup';
        $this->pickup_started_at = now();
        $this->save();
    }

    public function markCargoPickedUp()
    {
        $this->status = 'cargo_picked_up';
        $this->picked_up_at = now();
        $this->save();
    }

    public function markInTransitToPort()
    {
        $this->status = 'in_transit_to_port';
        $this->save();
    }

    public function markArrivedAtPort()
    {
        $this->status = 'arrived_at_port';
        $this->arrived_at_port_at = now();
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

    /**
     * Upload proof of arrival (cargo pickup)
     */
    public function uploadProofOfArrival($filePath)
    {
        $this->proof_of_arrival_file_path = $filePath;
        $this->save();
    }
}
