<?php $__env->startSection('title', 'Make Payment'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Make Payment</h1>
                                <p class="mt-2 text-gray-600">Invoice #<?php echo e($invoice->invoice_number); ?></p>
                            </div>
                            <a href="<?php echo e(route('customer.invoices.show', $invoice)); ?>" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                                Back to Invoice
                            </a>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Invoice Number</p>
                                <p class="font-medium"><?php echo e($invoice->invoice_number); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-medium"><?php echo e($invoice->order->order_number); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-medium text-lg">RM<?php echo e(number_format($invoice->amount, 2)); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <?php echo e($invoice->status); ?>

                                </span>
                            </div>
                        </div>
                        
                        <?php if($invoice->payments->count() > 0): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Previous Payments</p>
                            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between text-sm">
                                <span>Payment #<?php echo e($payment->id); ?> (<?php echo e($payment->payment_method); ?>)</span>
                                <span>RM<?php echo e(number_format($payment->amount, 2)); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between font-medium mt-2 pt-2 border-t border-gray-200">
                                <span>Remaining Balance</span>
                                <span>RM<?php echo e(number_format($invoice->amount - $invoice->payments->sum('amount'), 2)); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Payment Form -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                        
                        <?php if($errors->any()): ?>
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                            <div class="text-sm text-red-800">
                                <ul class="list-disc list-inside space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('customer.payments.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">
                                        Payment Amount (RM)
                                    </label>
                                    <input type="number" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="<?php echo e($invoice->amount - $invoice->payments->sum('amount')); ?>"
                                           value="<?php echo e($invoice->amount - $invoice->payments->sum('amount')); ?>"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                           required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Maximum amount: RM<?php echo e(number_format($invoice->amount - $invoice->payments->sum('amount'), 2)); ?>

                                    </p>
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">
                                        Payment Method
                                    </label>
                                    <select id="payment_method" 
                                            name="payment_method" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            required>
                                        <option value="">Select a payment method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="online_banking">Online Banking</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">
                                        Payment Date
                                    </label>
                                    <input type="date" 
                                           id="payment_date" 
                                           name="payment_date" 
                                           value="<?php echo e(now()->format('Y-m-d')); ?>"
                                           max="<?php echo e(now()->format('Y-m-d')); ?>"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                           required>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-4">
                                <a href="<?php echo e(route('customer.invoices.show', $invoice)); ?>" 
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/customer/payments/create.blade.php ENDPATH**/ ?>