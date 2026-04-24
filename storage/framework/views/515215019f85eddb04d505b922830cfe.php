<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    .glassmorphism {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .gradient-button {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
        transition: all 0.3s ease;
    }
    .gradient-button:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
    }
    .premium-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .premium-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .status-badge {
        transition: all 0.3s ease;
    }
    .loading-spinner {
        border: 2px solid #f3f4f6;
        border-top: 2px solid #2563eb;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen">
    <!-- Main Dashboard Content -->
    <main class="container mx-auto px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <!-- Page Title Section -->
            <div class="text-center mb-12 slide-in">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-full mb-6">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm font-medium text-blue-700">Live Dashboard Overview</span>
                </div>
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Dashboard Analytics</h2>
                <p class="text-xl text-slate-600 font-light">Real-time business metrics and workflow status</p>
            </div>

            <!-- Premium Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 fade-in">
                <!-- Card 1: Total Orders -->
                <div class="premium-card bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg border border-indigo-400 p-8 relative overflow-hidden">
                    <!-- Gradient Background Effect -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="flex items-center space-x-1 text-white/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-sm font-semibold">+12%</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-white/70 font-medium mb-2">Total Orders</p>
                            <p class="text-3xl font-bold text-white mb-1">
                                <?php echo e($stats['total_orders']); ?>

                            </p>
                            <p class="text-sm text-white/60">Last 30 days</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Paid Invoices -->
                <div class="premium-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg border border-emerald-400 p-8 relative overflow-hidden">
                    <!-- Gradient Background Effect -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="flex items-center space-x-1 text-white/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-sm font-semibold">+8%</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-white/70 font-medium mb-2">Paid Invoices</p>
                            <p class="text-3xl font-bold text-white mb-1">
                                <?php echo e($stats['paid_invoices']); ?> / <?php echo e($stats['total_invoices']); ?>

                            </p>
                            <p class="text-sm text-white/60"><?php echo e($stats['total_invoices'] > 0 ? round(($stats['paid_invoices'] / $stats['total_invoices']) * 100, 1) : 0); ?>% completion rate</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Delivery Status -->
                <div class="premium-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg border border-blue-400 p-8 relative overflow-hidden">
                    <!-- Gradient Background Effect -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                            <div class="flex items-center space-x-1 text-white/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-sm font-semibold">+15%</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-white/70 font-medium mb-2">Delivery Status</p>
                            <p class="text-3xl font-bold text-white mb-1">
                                <?php echo e($stats['shipped_count']); ?> / <?php echo e($stats['delivered_count']); ?>

                            </p>
                            <p class="text-sm text-white/60">In transit / Delivered</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in">
                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Recent Orders</h3>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-blue-600 hover:text-blue-500 text-sm font-medium">View All</a>
                    </div>
                    
                    <?php if($recentOrders->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900"><?php echo e($order->order_number); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo e($order->customer_name); ?></p>
                                            <p class="text-sm text-gray-500">RM<?php echo e(number_format($order->total_amount, 2)); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo e($order->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                <?php echo e($order->status); ?>

                                            </span>
                                            <br>
                                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-500 text-sm mt-2 inline-block">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                            <p class="mt-1 text-sm text-gray-500">No orders have been created yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Recent Invoices</h3>
                        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="text-blue-600 hover:text-blue-500 text-sm font-medium">View All</a>
                    </div>
                    
                    <?php if($recentInvoices->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900"><?php echo e($invoice->invoice_number); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo e($invoice->order ? $invoice->order->customer_name : 'N/A'); ?></p>
                                            <p class="text-sm text-gray-500">RM<?php echo e(number_format($invoice->amount, 2)); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($invoice->created_at->format('M d, Y')); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($invoice->status === 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                <?php echo e($invoice->status); ?>

                                            </span>
                                            <br>
                                            <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="text-blue-600 hover:text-blue-500 text-sm mt-2 inline-block">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices found</h3>
                            <p class="mt-1 text-sm text-gray-500">No invoices have been created yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

                    </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>