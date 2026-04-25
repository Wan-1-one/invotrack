<?php $__env->startSection('title', 'Record Payment'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Record Payment</h1>
            <p class="mt-1 text-sm text-gray-600">Invoice: <?php echo e($invoice->invoice_number); ?> - Amount: RM<?php echo e(number_format($invoice->amount, 2)); ?></p>
        </div>

        <!-- Payment Form -->
        <form method="POST" action="<?php echo e(route('admin.payments.store')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            
            <!-- Payment Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payment Details</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Payment Amount (RM) *
                            </label>
                            <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="<?php echo e(old('amount', min($invoice->amount - $invoice->payments->where('status', 'verified')->sum('amount'), $invoice->amount))); ?>">
                            <p class="mt-1 text-sm text-gray-500">
                                Remaining balance: RM<?php echo e(number_format($invoice->amount - $invoice->payments->where('status', 'verified')->sum('amount'), 2)); ?>

                            </p>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">
                                Payment Method *
                            </label>
                            <select name="payment_method" id="payment_method" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="bank_transfer" <?php echo e(old('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                <option value="cash" <?php echo e(old('payment_method') === 'cash' ? 'selected' : ''); ?>>Cash</option>
                                <option value="credit_card" <?php echo e(old('payment_method') === 'credit_card' ? 'selected' : ''); ?>>Credit Card</option>
                                <option value="other" <?php echo e(old('payment_method') === 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">
                                Payment Date *
                            </label>
                            <input type="date" name="payment_date" id="payment_date" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="<?php echo e(old('payment_date', now()->format('Y-m-d'))); ?>">
                        </div>

                        <div>
                            <label for="transaction_reference" class="block text-sm font-medium text-gray-700">
                                Transaction Reference
                            </label>
                            <input type="text" name="transaction_reference" id="transaction_reference"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="<?php echo e(old('transaction_reference')); ?>">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Payment Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo e(old('notes')); ?></textarea>
                    </div>

                    <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/payments/create.blade.php ENDPATH**/ ?>