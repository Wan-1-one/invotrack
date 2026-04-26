<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'quantity',
        'price_per_unit',
        'name_of_products',
        'transportation_type',
        'delivery_destination',
        'cargo_size',
        'type_of_goods',
        'notes',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the invoice associated with the order.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the customer who placed this order.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Generate unique order number with retry mechanism to prevent duplicates
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $year = date('Y');
        $month = date('m');
        $maxRetries = 5;

        for ($i = 0; $i < $maxRetries; $i++) {
            // Use lockForUpdate to prevent race conditions
            $lastOrder = self::where('order_number', 'like', "{$prefix}{$year}{$month}%")
                ->lockForUpdate()
                ->orderBy('order_number', 'desc')
                ->first();

            $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
            $orderNumber = "{$prefix}{$year}{$month}" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Check if this order number already exists (race condition check)
            if (!self::where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }

            // If exists, increment and try again
            $sequence++;
        }

        // Fallback: use timestamp if all retries fail
        return "{$prefix}{$year}{$month}" . str_pad(time() % 10000, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted status text
     */
    public function getFormattedStatusAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}
