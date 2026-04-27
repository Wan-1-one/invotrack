<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'document_number',
        'status',
        'content',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the order that owns the document.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate unique document number with retry mechanism to prevent duplicates
     */
    public static function generateDocumentNumber()
    {
        $prefix = 'DOC';
        $year = date('Y');
        $month = date('m');
        $maxRetries = 5;

        for ($i = 0; $i < $maxRetries; $i++) {
            // Use lockForUpdate to prevent race conditions
            $lastDocument = self::where('document_number', 'like', "{$prefix}{$year}{$month}%")
                ->lockForUpdate()
                ->orderBy('document_number', 'desc')
                ->first();

            $sequence = $lastDocument ? (int)substr($lastDocument->document_number, -4) + 1 : 1;
            $documentNumber = "{$prefix}{$year}{$month}" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Check if this document number already exists (race condition check)
            if (!self::where('document_number', $documentNumber)->exists()) {
                return $documentNumber;
            }

            // If exists, increment and try again
            $sequence++;
        }

        // Fallback: use timestamp if all retries fail
        return "{$prefix}{$year}{$month}" . str_pad(time() % 10000, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mark document as approved
     */
    public function markAsApproved()
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Generate document content from order data
     */
    public static function generateContentFromOrder(Order $order, $documentNumber = null)
    {
        $content = "CUSTOMS DOCUMENT\n";
        $content .= "================\n\n";
        $content .= "Document Number: " . ($documentNumber ?? 'Pending') . "\n";
        $content .= "Order Number: {$order->order_number}\n";
        $content .= "Customer Name: {$order->customer_name}\n";
        $content .= "Customer Email: {$order->customer_email}\n";
        $content .= "Customer Phone: {$order->customer_phone}\n";
        $content .= "Customer Address: {$order->customer_address}\n\n";
        $content .= "Order Details:\n";
        $content .= "- Quantity: {$order->quantity}\n";
        $content .= "- Product Name: {$order->name_of_products}\n";
        $content .= "- Price per Unit: RM" . number_format($order->price_per_unit, 2) . "\n";
        $content .= "- Total Amount: RM" . number_format($order->total_amount, 2) . "\n\n";
        $content .= "Shipment Details:\n";
        $content .= "- Transportation Type: " . str_replace('_', ' ', $order->transportation_type) . "\n";
        $content .= "- Delivery Destination: " . str_replace('_', ' ', $order->delivery_destination) . "\n";
        $content .= "- Cargo Size: " . ucfirst($order->cargo_size) . "\n";
        $content .= "- Type of Goods: " . str_replace('_', ' ', $order->type_of_goods) . "\n";
        
        if ($order->notes) {
            $content .= "\nNotes: {$order->notes}\n";
        }

        return $content;
    }

    /**
     * Get formatted status text
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return $this->status === 'approved' ? 'green' : 'yellow';
    }
}
