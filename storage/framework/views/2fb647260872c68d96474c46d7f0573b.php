<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-2 text-gray-600">Welcome to your logistics dashboard</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Orders</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['total_orders']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Pending Orders</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['pending_orders']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Confirmed Orders</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['confirmed_orders']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Spent</p>
                            <p class="text-2xl font-semibold text-gray-900">RM<?php echo e(number_format($stats['total_amount'], 2)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    </div>
                    <div class="p-6">
                        <?php if($recentOrders->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($order->order_number); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium">RM<?php echo e(number_format($order->total_amount, 2)); ?></p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                <?php echo e($order->status); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-4">
                                <a href="<?php echo e(route('customer.orders.index')); ?>" class="text-purple-600 hover:text-purple-900 font-medium">
                                    View all orders →
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No orders yet</p>
                                <a href="<?php echo e(route('customer.orders.create')); ?>" class="mt-2 text-purple-600 hover:text-purple-900 font-medium">
                                    Place your first order
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Shipments -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Active Shipments</h3>
                    </div>
                    <div class="p-6">
                        <?php if($activeShipments->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $activeShipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($shipment->tracking_number); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo e($shipment->courier_name); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium"><?php echo e($shipment->status); ?></p>
                                            <a href="<?php echo e(route('customer.shipments.track', $shipment)); ?>" 
                                               class="text-blue-600 hover:text-blue-900 text-sm">
                                                Track
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-4">
                                <a href="<?php echo e(route('customer.shipments.index')); ?>" class="text-purple-600 hover:text-purple-900 font-medium">
                                    View all shipments →
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No active shipments</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                </div>
                <div class="p-6">
                    <?php if($recentInvoices->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-4 py-2 text-sm"><?php echo e($invoice->invoice_number); ?></td>
                                            <td class="px-4 py-2 text-sm"><?php echo e($invoice->created_at->format('M d, Y')); ?></td>
                                            <td class="px-4 py-2 text-sm">RM<?php echo e(number_format($invoice->amount, 2)); ?></td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                       ($invoice->status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')); ?>">
                                                    <?php echo e($invoice->status); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <a href="<?php echo e(route('customer.invoices.show', $invoice)); ?>" 
                                                   class="text-purple-600 hover:text-purple-900">
                                                    View
                                                </a>
                                                <?php if($invoice->status !== 'paid'): ?>
                                                    <a href="<?php echo e(route('customer.payments.create', $invoice)); ?>" 
                                                       class="ml-2 text-green-600 hover:text-green-900">
                                                        Pay
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('customer.invoices.index')); ?>" class="text-purple-600 hover:text-purple-900 font-medium">
                                View all invoices →
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No invoices yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>