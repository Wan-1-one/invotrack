<?php $__env->startSection('title', 'Create Shipment'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create Shipment</h1>
            <p class="mt-1 text-sm text-gray-600">Invoice: <?php echo e($invoice->invoice_number); ?> - <?php echo e($invoice->order->customer_name); ?></p>
        </div>

        <!-- Shipment Form -->
        <form method="POST" action="<?php echo e(route('admin.shipments.store')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            
            <!-- Shipment Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Shipment Details</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="courier_name" class="block text-sm font-medium text-gray-700">
                                Courier Name *
                            </label>
                            <input type="text" name="courier_name" id="courier_name" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="<?php echo e(old('courier_name')); ?>">
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">
                                Shipping Address *
                            </label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo e(old('shipping_address', $invoice->order->customer_address)); ?></textarea>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Shipment Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Shipment
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/shipments/create.blade.php ENDPATH**/ ?>