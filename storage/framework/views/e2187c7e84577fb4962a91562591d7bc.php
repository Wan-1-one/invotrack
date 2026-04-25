<?php $__env->startSection('title', 'My Orders'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                        <p class="mt-2 text-gray-600">View and track your shoe orders</p>
                    </div>
                    <a href="<?php echo e(route('customer.orders.create')); ?>" 
                       class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-purple-700 hover:to-pink-700">
                        Place New Order
                    </a>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white rounded-lg shadow-lg">
                <?php if($orders->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order Number
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($order->order_number); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e($order->created_at->format('M d, Y')); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">RM<?php echo e(number_format($order->total_amount, 2)); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                <?php echo e($order->status); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($order->invoice): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php echo e($order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                       ($order->invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                    <?php echo e($order->invoice->status); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-sm text-gray-500">No invoice</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="<?php echo e(route('customer.orders.show', $order)); ?>" 
                                               class="text-purple-600 hover:text-purple-900 mr-3">
                                                View
                                            </a>
                                            <!-- Cancel Order - Only for pending orders without invoice -->
                                            <?php if($order->status === 'pending' && !$order->invoice): ?>
                                            <form action="<?php echo e(route('customer.orders.cancel', $order)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 mr-3">
                                                    Cancel
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                            <?php if($order->invoice && $order->invoice->shipment): ?>
                                                <a href="<?php echo e(route('customer.shipments.track', $order->invoice->shipment)); ?>" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Track
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <!-- Mobile pagination -->
                            <a href="<?php echo e($orders->previousPageUrl()); ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                            <a href="<?php echo e($orders->nextPageUrl()); ?>" 
                               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium"><?php echo e($orders->firstItem()); ?></span>
                                    to
                                    <span class="font-medium"><?php echo e($orders->lastItem()); ?></span>
                                    of
                                    <span class="font-medium"><?php echo e($orders->total()); ?></span>
                                    results
                                </p>
                            </div>
                            <div>
                                <?php echo e($orders->links()); ?>

                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by placing your first shoe order.</p>
                        
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('invotrack-order.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack-order/orders/index.blade.php ENDPATH**/ ?>