<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'INVOTRACK'); ?> - Invoice & Shipment Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .workflow-step {
            @apply flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium;
        }
        .workflow-step.completed {
            @apply bg-green-500 text-white;
        }
        .workflow-step.in_progress {
            @apply bg-blue-500 text-white;
        }
        .workflow-step.pending {
            @apply bg-gray-300 text-gray-600;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation Header -->
    <header class="bg-white border border-slate-200 sticky top-0 z-50 shadow-sm rounded-xl mx-4 mt-2">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">INVOTRACK</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <nav class="hidden md:flex space-x-8">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-600'); ?> hover:text-blue-600 transition-colors font-medium text-sm">Dashboard</a>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="<?php echo e(request()->routeIs('admin.orders.*') ? 'text-blue-600' : 'text-slate-600'); ?> hover:text-blue-600 transition-colors font-medium text-sm">Orders</a>
                        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="<?php echo e(request()->routeIs('admin.invoices.*') ? 'text-blue-600' : 'text-slate-600'); ?> hover:text-blue-600 transition-colors font-medium text-sm">Invoices</a>
                        <a href="<?php echo e(route('admin.payments.index')); ?>" class="<?php echo e(request()->routeIs('admin.payments.*') ? 'text-blue-600' : 'text-slate-600'); ?> hover:text-blue-600 transition-colors font-medium text-sm">Payments</a>
                        <a href="<?php echo e(route('admin.shipments.index')); ?>" class="<?php echo e(request()->routeIs('admin.shipments.*') ? 'text-blue-600' : 'text-slate-600'); ?> hover:text-blue-600 transition-colors font-medium text-sm">Shipments</a>
                        <a href="/" class="text-slate-600 hover:text-blue-600 transition-colors font-medium text-sm">Back to Home</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
            </div>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>