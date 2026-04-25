<?php $__env->startSection('title', 'Track Shipment'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Track Shipment</h1>
                        <p class="mt-2 text-gray-600">Tracking Number: <?php echo e($shipment->tracking_number); ?></p>
                    </div>
                    <a href="<?php echo e(route('customer.shipments.index')); ?>" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Back to Shipments
                    </a>
                </div>
            </div>

            <!-- Shipment Status Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Shipment Status</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-2
                            <?php echo e($shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                               ($shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                            <?php echo e($shipment->status); ?>

                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Order</p>
                        <p class="font-medium"><?php echo e($shipment->invoice->order->order_number); ?></p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium bg-green-100 text-green-700">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium">Order Placed</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium bg-green-100 text-green-700">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium">Package Ready</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                                <?php echo e($shipment->status === 'shipped' || $shipment->status === 'delivered' ? 'bg-green-100 text-green-700' : ($shipment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700 animate-pulse')); ?>">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium">In Transit</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                                <?php echo e($shipment->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium">Delivered</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-2 rounded-full" 
                             style="width: <?php echo e($shipment->status === 'delivered' ? '100' : ($shipment->status === 'shipped' ? '75' : '50')); ?>%"></div>
                    </div>
                </div>

                <!-- Shipment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipment Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tracking Number:</span>
                                <span class="font-medium"><?php echo e($shipment->tracking_number); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Courier:</span>
                                <span class="font-medium"><?php echo e($shipment->courier_name); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping Address:</span>
                                <span class="font-medium text-right max-w-xs"><?php echo e($shipment->shipping_address); ?></span>
                            </div>
                            <?php if($shipment->shipped_date): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipped Date:</span>
                                <span class="font-medium"><?php echo e($shipment->shipped_date->format('M d, Y')); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if($shipment->delivered_date): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivered Date:</span>
                                <span class="font-medium"><?php echo e($shipment->delivered_date->format('M d, Y')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-medium"><?php echo e($shipment->invoice->order->order_number); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Invoice Number:</span>
                                <span class="font-medium"><?php echo e($shipment->invoice->invoice_number); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span class="font-medium"><?php echo e($shipment->invoice->order->created_at->format('M d, Y')); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-medium">RM<?php echo e(number_format($shipment->invoice->amount, 2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="<?php echo e(route('customer.shipments.timeline', $shipment)); ?>" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    View Timeline
                </a>
                <?php if($shipment->pod_file_path): ?>
                <a href="<?php echo e(asset('storage/' . $shipment->pod_file_path)); ?>" 
                   target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    View Proof of Delivery
                </a>
                <?php endif; ?>
            </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/customer/shipments/track.blade.php ENDPATH**/ ?>