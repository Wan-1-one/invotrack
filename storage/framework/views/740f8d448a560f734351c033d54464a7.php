<?php $__env->startSection('title', 'Invoice Details'); ?>

<?php $__env->startSection('content'); ?>
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Invoice Details</h1>
                    <p class="mt-2 text-gray-600">Invoice <?php echo e($invoice->invoice_number); ?></p>
                </div>
                <a href="<?php echo e(route('admin.invoices.index')); ?>" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Invoices
                </a>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e($invoice->invoice_number); ?></h2>
                        <p class="text-gray-600 mt-1">Order: <?php echo e($invoice->order ? $invoice->order->order_number : 'N/A'); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                            <?php echo e($invoice->status); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Invoice Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Invoice Date:</span>
                            <span class="font-medium"><?php echo e($invoice->created_at->format('M d, Y')); ?></span>
                        </div>
                        <?php if($invoice->issue_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Issue Date:</span>
                            <span class="font-medium"><?php echo e($invoice->issue_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($invoice->due_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-medium"><?php echo e($invoice->due_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($invoice->paid_date): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid Date:</span>
                            <span class="font-medium"><?php echo e($invoice->paid_date->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-lg">RM<?php echo e(number_format($invoice->amount, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium"><?php echo e($invoice->order ? $invoice->order->order_number : 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium"><?php echo e($invoice->order ? $invoice->order->created_at->format('M d, Y') : 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($invoice->order && $invoice->order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->order && $invoice->order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                                <?php echo e($invoice->order ? $invoice->order->status : 'N/A'); ?>

                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer:</span>
                            <span class="font-medium"><?php echo e($invoice->order ? $invoice->order->customer_name : 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <?php if($invoice->payments->count() > 0): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
            <div class="space-y-4">
                <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

        <!-- Shipment Information -->
        <?php if($invoice->shipment): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tracking Number:</span>
                        <span class="font-medium"><?php echo e($invoice->shipment->tracking_number); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Courier:</span>
                        <span class="font-medium"><?php echo e($invoice->shipment->courier_name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php echo e($invoice->shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                               ($invoice->shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                            <?php echo e($invoice->shipment->status); ?>

                        </span>
                    </div>
                    <?php if($invoice->shipment->shipped_date): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipped Date:</span>
                        <span class="font-medium"><?php echo e($invoice->shipment->shipped_date->format('M d, Y')); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($invoice->shipment->delivered_date): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivered Date:</span>
                        <span class="font-medium"><?php echo e($invoice->shipment->delivered_date->format('M d, Y')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="space-y-3">
                    <a href="<?php echo e(route('admin.shipments.track', $invoice->shipment)); ?>" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block mb-2">
                        Track Shipment
                    </a>
                    <?php if($invoice->shipment->pod_file_path): ?>
                    <a href="<?php echo e(asset('storage/' . $invoice->shipment->pod_file_path)); ?>" 
                       target="_blank"
                       class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium block">
                        View Proof of Delivery
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <?php if($invoice->status === 'draft'): ?>
                <form action="<?php echo e(route('admin.invoices.issue', $invoice)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Issue Invoice
                    </button>
                </form>
            <?php endif; ?>
            <?php if($invoice->order): ?>
                <a href="<?php echo e(route('admin.orders.show', $invoice->order)); ?>" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    View Order Details
                </a>
            <?php endif; ?>
            <?php if($invoice->shipment): ?>
                <a href="<?php echo e(route('admin.shipments.track', $invoice->shipment)); ?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Track Shipment
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('invotrack.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack/invoices/show.blade.php ENDPATH**/ ?>