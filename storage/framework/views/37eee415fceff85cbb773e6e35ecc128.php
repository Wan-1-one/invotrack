<?php $__env->startSection('title', 'Place Order'); ?>

<?php $__env->startSection('content'); ?>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Place New Order</h1>
                <p class="mt-2 text-gray-600">Fill in the details below to place your shoe order</p>
            </div>

            <!-- Order Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="<?php echo e(route('customer.orders.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- Error Messages -->
                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div class="text-sm text-red-800">
                                <?php echo e($errors->first()); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Customer Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name *
                                        </label>
                                        <input type="text" id="customer_name" name="customer_name" required
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                               placeholder="Enter your full name">
                                    </div>

                                    
                                    <div>
                                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number
                                        </label>
                                        <input type="tel" id="customer_phone" name="customer_phone"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                               placeholder="+60 12-3456789">
                                    </div>

                                    <div>
                                        <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                            Delivery Address *
                                        </label>
                                        <textarea id="customer_address" name="customer_address" rows="3" required
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                                  placeholder="Enter your complete delivery address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Product Name *
                                        </label>
                                        <select id="product_name" name="product_name" required
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <option value="">Select a product</option>
                                            <option value="Nike Air Max 270">Nike Air Max 270 - RM450</option>
                                            <option value="Adidas Ultraboost 22">Adidas Ultraboost 22 - RM520</option>
                                            <option value="New Balance 574">New Balance 574 - RM380</option>
                                            <option value="Converse Chuck Taylor">Converse Chuck Taylor - RM280</option>
                                            <option value="Vans Old Skool">Vans Old Skool - RM320</option>
                                            <option value="Puma RS-X">Puma RS-X - RM410</option>
                                            <option value="Reebok Classic">Reebok Classic - RM350</option>
                                            <option value="ASICS Gel-Kayano">ASICS Gel-Kayano - RM580</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Quantity *
                                        </label>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1" required
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                               onchange="calculateTotal()">
                                    </div>

                                    <div>
                                        <label for="price_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                            Price per Unit (RM) *
                                        </label>
                                        <input type="number" id="price_per_unit" name="price_per_unit" step="0.01" min="0.01" required
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                               onchange="calculateTotal()">
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Order Notes (Optional)
                                        </label>
                                        <textarea id="notes" name="notes" rows="3"
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                                  placeholder="Any special requests or notes about your order"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Product:</span>
                                            <span id="summary-product" class="font-medium">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Quantity:</span>
                                            <span id="summary-quantity" class="font-medium">0</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Unit Price:</span>
                                            <span id="summary-price" class="font-medium">RM0.00</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <div class="flex justify-between">
                                                <span class="text-lg font-semibold">Total Amount:</span>
                                                <span id="summary-total" class="text-lg font-bold text-purple-600">RM0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
                                
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="space-y-2">
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-blue-900">Delivery Address</p>
                                                <p class="text-sm text-blue-800">Will be taken from customer information</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-blue-900">Contact Phone</p>
                                                <p class="text-sm text-blue-800">Will be taken from customer information</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Product prices mapping
        const productPrices = {
            'Nike Air Max 270': 450,
            'Adidas Ultraboost 22': 520,
            'New Balance 574': 380,
            'Converse Chuck Taylor': 280,
            'Vans Old Skool': 320,
            'Puma RS-X': 410,
            'Reebok Classic': 350,
            'ASICS Gel-Kayano': 580
        };

        // Auto-fill price when product is selected
        document.getElementById('product_name').addEventListener('change', function() {
            const selectedProduct = this.value;
            const priceField = document.getElementById('price_per_unit');
            
            if (productPrices[selectedProduct]) {
                priceField.value = productPrices[selectedProduct];
            } else {
                priceField.value = '';
            }
            
            calculateTotal();
        });

        // Calculate total amount
        function calculateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('price_per_unit').value) || 0;
            const product = document.getElementById('product_name').value;
            
            const total = quantity * price;
            
            // Update summary
            document.getElementById('summary-product').textContent = product || '-';
            document.getElementById('summary-quantity').textContent = quantity;
            document.getElementById('summary-price').textContent = `RM${price.toFixed(2)}`;
            document.getElementById('summary-total').textContent = `RM${total.toFixed(2)}`;
        }

        // Initialize calculation on page load
        calculateTotal();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/customer/orders/create.blade.php ENDPATH**/ ?>