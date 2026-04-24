<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order <?php echo e($order->order_number); ?></h1>
                <p class="mt-1 text-sm text-gray-600">Created on <?php echo e($order->created_at->format('M d, Y')); ?></p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Edit Order
                </a>
            </div>
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Customer Information</h3>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->customer_name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->customer_email); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->customer_phone ?: 'Not provided'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                        <?php echo e($order->status); ?>

                                    </span>
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->customer_address); ?></dd>
                        </div>

                        <?php if($order->notes): ?>
                            <div class="mt-4">
                                <dt class="text-sm font-medium text-gray-500">Order Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->notes); ?></dd>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Invoice Information -->
                <?php if($order->invoice): ?>
                    <div class="bg-white shadow sm:rounded-lg mt-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Invoice Information</h3>
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Invoice #<?php echo e($order->invoice->invoice_number); ?>

                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Amount: RM<?php echo e(number_format($order->invoice->amount, 2)); ?>

                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Status: 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            <?php echo e($order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($order->invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                                            <?php echo e($order->invoice->status); ?>

                                        </span>
                                    </p>
                                </div>
                                <a href="<?php echo e(route('admin.invoices.show', $order->invoice)); ?>" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    View Invoice
                                </a>
                            </div>

                            <!-- Workflow Progress -->
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Workflow Progress</p>
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        <div class="workflow-step <?php echo e($order->invoice->getWorkflowStatus()['order'] === 'completed' ? 'completed' : 'pending'); ?>">1</div>
                                        <span class="ml-2 text-xs text-gray-600">Order</span>
                                    </div>
                                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                                    <div class="flex items-center">
                                        <div class="workflow-step <?php echo e($order->invoice->getWorkflowStatus()['invoice'] === 'completed' ? 'completed' : ($order->invoice->getWorkflowStatus()['invoice'] === 'in_progress' ? 'in_progress' : 'pending')); ?>">2</div>
                                        <span class="ml-2 text-xs text-gray-600">Invoice</span>
                                    </div>
                                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                                    <div class="flex items-center">
                                        <div class="workflow-step <?php echo e($order->invoice->getWorkflowStatus()['payment'] === 'completed' ? 'completed' : ($order->invoice->getWorkflowStatus()['payment'] === 'in_progress' ? 'in_progress' : 'pending')); ?>">3</div>
                                        <span class="ml-2 text-xs text-gray-600">Payment</span>
                                    </div>
                                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                                    <div class="flex items-center">
                                        <div class="workflow-step <?php echo e($order->invoice->getWorkflowStatus()['shipment'] === 'completed' ? 'completed' : ($order->invoice->getWorkflowStatus()['shipment'] === 'in_progress' ? 'in_progress' : 'pending')); ?>">4</div>
                                        <span class="ml-2 text-xs text-gray-600">Shipment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order Summary</h3>
                        
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($order->order_number); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                <dd class="text-sm font-medium text-gray-900">RM<?php echo e(number_format($order->total_amount, 2)); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($order->created_at->format('M d, Y')); ?></dd>
                            </div>
                        </dl>

                        <?php if($order->invoice && $order->invoice->status === 'issued'): ?>
                            <div class="mt-6 space-y-3">
                                <a href="<?php echo e(route('admin.payments.create', $order->invoice)); ?>" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Record Payment
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($order->invoice && $order->invoice->status === 'paid' && !$order->invoice->shipment): ?>
                            <div class="mt-6 space-y-3">
                                <a href="<?php echo e(route('admin.shipments.create', $order->invoice)); ?>" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Create Shipment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/orders/show.blade.php ENDPATH**/ ?>