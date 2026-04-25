<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Customer Dashboard'); ?> - Invoice System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white border border-slate-200 sticky top-0 z-50 shadow-sm rounded-xl mx-4 mt-2">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-slate-900">Invotrack Order</span>
                </div>
                <div class="flex items-center space-x-4">
                    <nav class="hidden md:flex space-x-8">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" class="<?php echo e(request()->routeIs('customer.dashboard') ? 'text-purple-600' : 'text-slate-600'); ?> hover:text-purple-600 transition-colors font-medium text-sm">Dashboard</a>
                        <a href="<?php echo e(route('customer.orders.create')); ?>" class="<?php echo e(request()->routeIs('customer.orders.create') ? 'text-purple-600' : 'text-slate-600'); ?> hover:text-purple-600 transition-colors font-medium text-sm">Place Order</a>
                        <a href="<?php echo e(route('customer.orders.index')); ?>" class="<?php echo e(request()->routeIs('customer.orders.index') ? 'text-purple-600' : 'text-slate-600'); ?> hover:text-purple-600 transition-colors font-medium text-sm">My Orders</a>
                        <a href="<?php echo e(route('customer.invoices.index')); ?>" class="<?php echo e(request()->routeIs('customer.invoices.*') ? 'text-purple-600' : 'text-slate-600'); ?> hover:text-purple-600 transition-colors font-medium text-sm">Invoices</a>
                        <a href="<?php echo e(route('customer.shipments.index')); ?>" class="<?php echo e(request()->routeIs('customer.shipments.*') ? 'text-purple-600' : 'text-slate-600'); ?> hover:text-purple-600 transition-colors font-medium text-sm">Track Orders</a>
                        <a href="/" class="text-slate-600 hover:text-purple-600 transition-colors font-medium text-sm">Back to Home</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\invotrack-laravel\resources\views/invotrack-order/layouts/app.blade.php ENDPATH**/ ?>