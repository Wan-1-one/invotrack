<?php $__env->startSection('title', 'Invoice #' . $invoice->invoice_number); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Invoice #<?php echo e($invoice->invoice_number); ?></h1>
                        <p class="mt-2 text-gray-600">Invoice details and payment information</p>
                    </div>
                    <div class="flex space-x-3">
                        <?php if($invoice->status !== 'paid'): ?>
                            <a href="<?php echo e(route('customer.payments.create', $invoice)); ?>" 
                               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                                Make Payment
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('customer.invoices.index')); ?>" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Back to Invoices
                        </a>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Invoice Header -->
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                        <p class="text-gray-600 mt-1">#<?php echo e($invoice->invoice_number); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Status:</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')); ?>">
                            <?php echo e($invoice->status); ?>

                        </span>
                    </div>
                </div>

                <!-- Bill To and Order Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill To:</h3>
                        <div class="text-gray-700">
                            <p class="font-medium"><?php echo e($invoice->order->customer_name); ?></p>
                            <p><?php echo e($invoice->order->customer_email); ?></p>
                            <p><?php echo e($invoice->order->customer_phone ?? 'N/A'); ?></p>
                            <p><?php echo e($invoice->order->customer_address); ?></p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information:</h3>
                        <div class="text-gray-700">
                            <p><span class="font-medium">Order Number:</span> <?php echo e($invoice->order->order_number); ?></p>
                            <p><span class="font-medium">Order Date:</span> <?php echo e($invoice->order->created_at->format('M d, Y')); ?></p>
                            <p><span class="font-medium">Invoice Date:</span> <?php echo e($invoice->created_at->format('M d, Y')); ?></p>
                            <?php if($invoice->paid_date): ?>
                                <p><span class="font-medium">Paid Date:</span> <?php echo e($invoice->paid_date->format('M d, Y')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items:</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 text-gray-700">Description</th>
                                <th class="text-center py-2 text-gray-700">Quantity</th>
                                <th class="text-right py-2 text-gray-700">Unit Price</th>
                                <th class="text-right py-2 text-gray-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="py-4">
                                    <div class="text-gray-900">
                                        <p class="font-medium"><?php echo e($invoice->order->name_of_products ?? 'Products'); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($invoice->order->transportation_type); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($invoice->order->delivery_destination); ?></p>
                                    </div>
                                </td>
                                <td class="text-center py-4"><?php echo e($invoice->order->quantity); ?></td>
                                <td class="text-right py-4">RM<?php echo e(number_format($invoice->order->price_per_unit, 2)); ?></td>
                                <td class="text-right py-4">RM<?php echo e(number_format($invoice->order->quantity * $invoice->order->price_per_unit, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="flex justify-end mb-8">
                    <div class="w-64">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">RM<?php echo e(number_format($invoice->order->quantity * $invoice->order->price_per_unit, 2)); ?></span>
                        </div>
                        <div class="flex justify-between py-2 font-semibold text-lg border-t">
                            <span>Total:</span>
                            <span>RM<?php echo e(number_format($invoice->amount, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <?php if($invoice->payments->count() > 0): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment History:</h3>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium"><?php echo e($payment->payment_method); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($payment->payment_date->format('M d, Y')); ?></p>
                                        <?php if($payment->reference_number): ?>
                                            <p class="text-sm text-gray-600">Ref: <?php echo e($payment->reference_number); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">RM<?php echo e(number_format($payment->amount, 2)); ?></p>
                                        <p class="text-sm text-green-600"><?php echo e($payment->status); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Shipment Information -->
                <?php if($invoice->shipment): ?>
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Tracking Number:</p>
                                <p class="font-medium"><?php echo e($invoice->shipment->tracking_number); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <p class="font-medium"><?php echo e($invoice->shipment->status); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Courier:</p>
                                <p class="font-medium"><?php echo e($invoice->shipment->courier_name); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Estimated Delivery:</p>
                                <p class="font-medium"><?php echo e($invoice->shipment->estimated_delivery ? $invoice->shipment->estimated_delivery->format('M d, Y') : 'N/A'); ?></p>
                            </div>
                        </div>
                        <?php if($invoice->shipment->status !== 'delivered'): ?>
                            <div class="mt-4">
                                <a href="<?php echo e(route('customer.shipments.track', $invoice->shipment)); ?>" 
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    Track Shipment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/customer/invoices/show.blade.php ENDPATH**/ ?>