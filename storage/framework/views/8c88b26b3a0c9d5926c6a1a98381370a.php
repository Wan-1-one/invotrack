<?php $__env->startSection('title', 'Shipments'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Shipments</h1>
        </div>

        <!-- Shipments Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div>
                                        <a href="<?php echo e(route('admin.shipments.track', $shipment)); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                            <?php echo e($shipment->tracking_number); ?>

                                        </a>
                                        <p class="text-sm text-gray-500">
                                            Invoice: <?php echo e($shipment->invoice->invoice_number); ?> - <?php echo e($shipment->invoice->order->customer_name); ?>

                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Courier: <?php echo e($shipment->courier_name); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            <?php echo e($shipment->created_at->format('M d, Y')); ?>

                                        </p>
                                        <?php if($shipment->shipped_date): ?>
                                            <p class="text-sm text-gray-500">
                                                Shipped: RM <?php echo e(number_format($shipment->shipped_date->format('M d, Y'))); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo e($shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           ($shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                        <?php echo e($shipment->status); ?>

                                    </span>
                                    <?php if($shipment->pod_file_path): ?>
                                        <a href="<?php echo e(asset('storage/' . $shipment->pod_file_path)); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            POD
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="px-4 py-8 text-center text-gray-500">
                        No shipments found. Shipments are created after invoices are paid.
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Pagination -->
        <?php if($shipments->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($shipments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/shipments/index.blade.php ENDPATH**/ ?>