<?php $__env->startSection('title', 'Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <a href="<?php echo e(route('admin.orders.create')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Create Order
            </a>
        </div>

        <!-- Orders Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600">
                                                <?php echo e($order->order_number); ?>

                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?php echo e($order->customer_name); ?> - <?php echo e($order->customer_email); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                RM<?php echo e(number_format($order->total_amount, 2)); ?>

                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?php echo e($order->created_at->format('M d, Y')); ?>

                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                            <?php echo e($order->status); ?>

                                        </span>
                                        <!-- Delete Button - Only for pending orders without invoice -->
                                        <?php if($order->status === 'pending' && !$order->invoice): ?>
                                        <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if($order->invoice): ?>
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">
                                            Invoice: <?php echo e($order->invoice->invoice_number); ?> (<?php echo e($order->invoice->status); ?>)
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="px-4 py-8 text-center text-gray-500">
                        No orders found. <a href="<?php echo e(route('admin.orders.create')); ?>" class="text-indigo-600 hover:text-indigo-900">Create your first order</a>.
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Pagination -->
        <?php if($orders->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($orders->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/orders/index.blade.php ENDPATH**/ ?>