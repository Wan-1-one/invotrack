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
                <a href="<?php echo e(route('admin.shipments.index')); ?>" 
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
                        <?php echo e($shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-800' : 
                           ($shipment->status === 'in_transit_to_port' ? 'bg-blue-100 text-blue-800' : 
                           ($shipment->status === 'cargo_picked_up' ? 'bg-indigo-100 text-indigo-800' :
                           ($shipment->status === 'en_route_to_pickup' ? 'bg-purple-100 text-purple-800' :
                           ($shipment->status === 'lorry_assigned' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))))); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Order</p>
                    <p class="font-medium"><?php echo e($shipment->invoice && $shipment->invoice->order ? $shipment->invoice->order->order_number : 'N/A'); ?></p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2 overflow-x-auto">
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium bg-green-100 text-green-700">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium">Booking Confirmed</span>
                    </div>
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                            <?php echo e($shipment->status === 'lorry_assigned' || $shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium">Lorry Assigned</span>
                    </div>
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                            <?php echo e($shipment->status === 'en_route_to_pickup' || $shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium">En Route to Pickup</span>
                    </div>
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                            <?php echo e($shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium">Cargo Picked Up</span>
                    </div>
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                            <?php echo e($shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            5
                        </div>
                        <span class="ml-2 text-sm font-medium">In Transit to Port</span>
                    </div>
                    <div class="flex items-center min-w-max">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium 
                            <?php echo e($shipment->status === 'arrived_at_port' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            6
                        </div>
                        <span class="ml-2 text-sm font-medium">Arrived at Port</span>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 h-2 rounded-full" 
                         style="width: <?php echo e($shipment->status === 'arrived_at_port' ? '100' : 
                            ($shipment->status === 'in_transit_to_port' ? '83' : 
                            ($shipment->status === 'cargo_picked_up' ? '67' : 
                            ($shipment->status === 'en_route_to_pickup' ? '50' : 
                            ($shipment->status === 'lorry_assigned' ? '33' : '17'))))); ?>%"></div>
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
                            <span class="font-medium"><?php echo e($shipment->invoice && $shipment->invoice->order ? $shipment->invoice->order->order_number : 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Invoice Number:</span>
                            <span class="font-medium"><?php echo e($shipment->invoice ? $shipment->invoice->invoice_number : 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium"><?php echo e($shipment->invoice && $shipment->invoice->order ? $shipment->invoice->order->created_at->format('M d, Y') : 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium"><?php echo e($shipment->invoice ? 'RM' . number_format($shipment->invoice->amount, 2) : 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <?php if($shipment->invoice && $shipment->invoice->payments->count() > 0): ?>
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $shipment->invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">Payment #<?php echo e($payment->id); ?></p>
                                <p class="text-sm text-gray-600">Amount: RM<?php echo e(number_format($payment->amount, 2)); ?></p>
                                <p class="text-sm text-gray-600">Method: <?php echo e($payment->payment_method); ?></p>
                                <p class="text-sm text-gray-600">Date: <?php echo e($payment->payment_date->format('M d, Y - h:i A')); ?></p>
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

        <!-- Admin Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Update Status -->
                <form action="<?php echo e(route('admin.shipments.updateStatus', $shipment)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="booking_confirmed" <?php echo e($shipment->status === 'booking_confirmed' ? 'selected' : ''); ?>>Booking Confirmed</option>
                                <?php if($shipment->invoice && $shipment->invoice->status === 'paid'): ?>
                                <option value="lorry_assigned" <?php echo e($shipment->status === 'lorry_assigned' ? 'selected' : ''); ?>>Lorry Assigned</option>
                                <option value="en_route_to_pickup" <?php echo e($shipment->status === 'en_route_to_pickup' ? 'selected' : ''); ?>>En Route to Pickup</option>
                                <option value="cargo_picked_up" <?php echo e($shipment->status === 'cargo_picked_up' ? 'selected' : ''); ?>>Cargo Picked Up</option>
                                <option value="in_transit_to_port" <?php echo e($shipment->status === 'in_transit_to_port' ? 'selected' : ''); ?>>In Transit to Port</option>
                                <option value="arrived_at_port" <?php echo e($shipment->status === 'arrived_at_port' ? 'selected' : ''); ?>>Arrived at Port</option>
                                <?php else: ?>
                                <option value="lorry_assigned" disabled>Lorry Assigned (Payment Required)</option>
                                <option value="en_route_to_pickup" disabled>En Route to Pickup (Payment Required)</option>
                                <option value="cargo_picked_up" disabled>Cargo Picked Up (Payment Required)</option>
                                <option value="in_transit_to_port" disabled>In Transit to Port (Payment Required)</option>
                                <option value="arrived_at_port" disabled>Arrived at Port (Payment Required)</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Update
                        </button>
                    </div>
                    <?php if($shipment->invoice && $shipment->invoice->status !== 'paid'): ?>
                    <p class="text-xs text-yellow-600 mt-1">⚠️ Customer must make payment before assigning lorry</p>
                    <?php endif; ?>
                </form>

                <!-- Upload Proof of Arrival (Cargo Pickup) -->
                <?php if($shipment->status === 'cargo_picked_up' || $shipment->status === 'in_transit_to_port' || $shipment->status === 'arrived_at_port'): ?>
                <form action="<?php echo e(route('admin.shipments.uploadProofOfArrival', $shipment)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Proof of Arrival (Cargo Pickup)</label>
                            <input type="file" name="arrival_file" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Upload
                        </button>
                    </div>
                </form>
                <?php endif; ?>

                <!-- Upload Proof of Delivery (Arrived at Port) -->
                <?php if($shipment->status === 'arrived_at_port'): ?>
                <form action="<?php echo e(route('admin.shipments.uploadPOD', $shipment)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Proof of Delivery (Arrived at Port)</label>
                            <input type="file" name="pod_file" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Upload
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="<?php echo e(route('admin.shipments.timeline', $shipment)); ?>" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Timeline
            </a>
            <?php if($shipment->proof_of_arrival_file_path): ?>
            <a href="<?php echo e(asset('storage/' . $shipment->proof_of_arrival_file_path)); ?>" 
               target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Proof of Arrival
            </a>
            <?php endif; ?>
            <?php if($shipment->pod_file_path): ?>
            <a href="<?php echo e(asset('storage/' . $shipment->pod_file_path)); ?>" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Proof of Delivery
            </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack/shipments/track.blade.php ENDPATH**/ ?>