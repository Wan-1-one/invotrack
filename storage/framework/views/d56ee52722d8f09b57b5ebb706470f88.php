<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
    <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                        <p class="mt-2 text-gray-600">Order <?php echo e($order->order_number); ?></p>
                    </div>
                    <a href="<?php echo e(route('customer.orders.index')); ?>" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Back to Orders
                    </a>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Order Status</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-2
                            <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                            <?php echo e($order->status); ?>

                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Order Date</p>
                        <p class="font-medium"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Order Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium"><?php echo e($order->order_number); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium">RM<?php echo e(number_format($order->total_amount, 2)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer Name:</span>
                            <span class="font-medium"><?php echo e($order->customer_name); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium"><?php echo e($order->customer_email); ?></span>
                        </div>
                        <?php if($order->customer_phone): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium"><?php echo e($order->customer_phone); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Address:</span>
                            <span class="font-medium text-right max-w-xs"><?php echo e($order->customer_address); ?></span>
                        </div>
                        <?php if($order->notes): ?>
                        <div>
                            <span class="text-gray-600">Notes:</span>
                            <p class="mt-1 text-sm"><?php echo e($order->notes); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Invoice Information -->
                <?php if($order->invoice): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Invoice Number:</span>
                            <span class="font-medium"><?php echo e($order->invoice->invoice_number); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">RM<?php echo e(number_format($order->invoice->amount, 2)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($order->invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                                <?php echo e($order->invoice->status); ?>

                            </span>
                        </div>
                        <?php if($order->invoice->issue_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Issue Date:</span>
                            <span class="font-medium"><?php echo e($order->invoice->issue_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->invoice->due_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-medium"><?php echo e($order->invoice->due_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->invoice->paid_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid Date:</span>
                            <span class="font-medium"><?php echo e($order->invoice->paid_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo e(route('customer.invoices.show', $order->invoice)); ?>" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                            View Invoice Details
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Shipment Information -->
            <?php if($order->invoice && $order->invoice->shipment): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tracking Number:</span>
                            <span class="font-medium"><?php echo e($order->invoice->shipment->tracking_number); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Courier:</span>
                            <span class="font-medium"><?php echo e($order->invoice->shipment->courier_name); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($order->invoice->shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($order->invoice->shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                <?php echo e($order->invoice->shipment->status); ?>

                            </span>
                        </div>
                        <?php if($order->invoice->shipment->shipped_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipped Date:</span>
                            <span class="font-medium"><?php echo e($order->invoice->shipment->shipped_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->invoice->shipment->delivered_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivered Date:</span>
                            <span class="font-medium"><?php echo e($order->invoice->shipment->delivered_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="<?php echo e(route('customer.shipments.track', $order->invoice->shipment)); ?>" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block mb-2">
                            Track Shipment
                        </a>
                        <?php if($order->invoice->shipment->pod_file_path): ?>
                        <a href="<?php echo e(asset('storage/' . $order->invoice->shipment->pod_file_path)); ?>" 
                           target="_blank"
                           class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                            View Proof of Delivery
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payment Information -->
            <?php if($order->invoice && $order->invoice->payments->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $order->invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">Payment #<?php echo e($payment->id); ?></p>
                                <p class="text-sm text-gray-600">Amount: RM<?php echo e(number_format($payment->amount, 2)); ?></p>
                                <p class="text-sm text-gray-600">Method: <?php echo e($payment->payment_method); ?></p>
                                <p class="text-sm text-gray-600">Date: <?php echo e($payment->payment_date->format('M d, Y')); ?></p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($payment->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e($payment->status); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('invotrack-order.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack-order/orders/show.blade.php ENDPATH**/ ?>