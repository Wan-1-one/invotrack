<?php

namespace App\Helpers;

class NotificationHelper
{
    /**
     * Success notification with icon
     */
    public static function success($message)
    {
        session()->flash('success', $message);
        session()->flash('notification_type', 'success');
    }

    /**
     * Error notification with icon
     */
    public static function error($message)
    {
        session()->flash('error', $message);
        session()->flash('notification_type', 'error');
    }

    /**
     * Warning notification with icon
     */
    public static function warning($message)
    {
        session()->flash('warning', $message);
        session()->flash('notification_type', 'warning');
    }

    /**
     * Info notification with icon
     */
    public static function info($message)
    {
        session()->flash('info', $message);
        session()->flash('notification_type', 'info');
    }

    /**
     * Auto-triggered notifications for system events
     */
    public static function orderCreated($orderNumber)
    {
        self::success("Order #{$orderNumber} created successfully!");
    }

    public static function invoiceGenerated($invoiceNumber)
    {
        self::success("Invoice #{$invoiceNumber} has been generated and sent to customer.");
    }

    public static function paymentReceived($paymentAmount, $invoiceNumber)
    {
        self::success("Payment of {$paymentAmount} received for Invoice #{$invoiceNumber}.");
    }

    public static function paymentVerified($paymentId)
    {
        self::success("Payment #{$paymentId} has been verified and processed.");
    }

    public static function shipmentCreated($trackingNumber)
    {
        self::success("Shipment created with tracking number: {$trackingNumber}");
    }

    public static function shipmentDelivered($trackingNumber)
    {
        self::success("Shipment {$trackingNumber} has been marked as delivered.");
    }

    public static function orderCompleted($orderNumber)
    {
        self::success("Order #{$orderNumber} has been completed successfully!");
    }
}
