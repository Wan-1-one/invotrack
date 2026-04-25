<?php $__env->startSection('title', 'Shipment Timeline'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Shipment Timeline</h1>
                        <p class="mt-2 text-gray-600">Tracking Number: <?php echo e($shipment->tracking_number); ?></p>
                    </div>
                    <a href="<?php echo e(route('customer.shipments.track', $shipment)); ?>" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Back to Tracking
                    </a>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="relative pb-8">
                                    <?php if($loop->last): ?>
                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-transparent" aria-hidden="true"></span>
                                    <?php else: ?>
                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <?php endif; ?>
                                    <div class="relative flex items-start space-x-3">
                                        <div>
                                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white
                                                <?php echo e($event['status'] === 'completed' ? 'bg-green-500' : 'bg-gray-400'); ?>">
                                                <?php if($event['icon'] === 'shopping-cart'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                    </svg>
                                                <?php elseif($event['icon'] === 'file-invoice'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                <?php elseif($event['icon'] === 'credit-card'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                <?php elseif($event['icon'] === 'package'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                <?php elseif($event['icon'] === 'truck'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                                    </svg>
                                                <?php elseif($event['icon'] === 'check-circle'): ?>
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 py-0">
                                            <div class="text-md font-medium text-gray-900"><?php echo e($event['title']); ?></div>
                                            <p class="text-sm text-gray-500"><?php echo e($event['description']); ?></p>
                                            <div class="mt-2">
                                                <span class="text-sm text-gray-400"><?php echo e($event['date']->format('M d, Y - h:i A')); ?></span>
                                                <?php if($event['status'] === 'completed'): ?>
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                <?php else: ?>
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Pending
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="<?php echo e(route('customer.shipments.track', $shipment)); ?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Back to Tracking
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('invotrack-order.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack-order/shipments/timeline.blade.php ENDPATH**/ ?>