<?php $__env->startSection('title', 'Payments'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        </div>

        <!-- Payments Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            RM<?php echo e(number_format($payment->amount, 2)); ?> - <?php echo e($payment->payment_method); ?>

                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Invoice: <?php echo e($payment->invoice->invoice_number); ?> - <?php echo e($payment->invoice->order->customer_name); ?>

                                        </p>
                                        <?php if($payment->transaction_reference): ?>
                                            <p class="text-sm text-gray-500">
                                                Reference: <?php echo e($payment->transaction_reference); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            <?php echo e($payment->payment_date->format('M d, Y')); ?>

                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo e($payment->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                        <?php echo e($payment->status); ?>

                                    </span>
                                    <?php if($payment->status === 'pending'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.payments.verify', $payment)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                Verify
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="px-4 py-8 text-center text-gray-500">
                        No payments found. Payments are recorded for issued invoices.
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Pagination -->
        <?php if($payments->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($payments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/payments/index.blade.php ENDPATH**/ ?>