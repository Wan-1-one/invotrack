<?php $__env->startSection('title', 'Track Shipment - ' . $shipment->tracking_number); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    .tracking-timeline {
        position: relative;
        padding-left: 2rem;
    }
    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #10b981, #6b7280);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: white;
        border: 3px solid #10b981;
    }
    .timeline-item.completed::before {
        background: #10b981;
        border-color: #10b981;
    }
    .timeline-item.current::before {
        background: #3b82f6;
        border-color: #3b82f6;
        animation: pulse 2s infinite;
    }
    .timeline-item.pending::before {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 py-6 sm:px-0">
    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Track Shipment</h1>
                    <p class="mt-1 text-sm text-gray-600">Tracking Number: <?php echo e($shipment->tracking_number); ?></p>
                </div>
                <a href="<?php echo e(route('admin.shipments.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Back to Shipments
                </a>
            </div>
        </div>

        <!-- Shipment Info Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Invoice</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        <a href="<?php echo e(route('admin.invoices.show', $shipment->invoice)); ?>" class="text-indigo-600 hover:text-indigo-800">
                            <?php echo e($shipment->invoice->invoice_number); ?>

                        </a>
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                    <p class="text-lg font-semibold text-gray-900"><?php echo e($shipment->invoice->order->customer_name); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Courier</h3>
                    <p class="text-lg font-semibold text-gray-900"><?php echo e($shipment->courier_name); ?></p>
                </div>
            </div>
        </div>

        <!-- Map and Tracking Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Map -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Location</h2>
                <div id="map"></div>
                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Current Location</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($currentLocation['city']); ?>, <?php echo e($currentLocation['state']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500">Estimated Delivery</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($currentLocation['estimated_delivery']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Overview -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipment Status</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="font-medium text-gray-900">Order Placed</span>
                        </div>
                        <span class="text-sm text-gray-500">Completed</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="font-medium text-gray-900">Package Ready</span>
                        </div>
                        <span class="text-sm text-gray-500">Completed</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 <?php echo e($shipment->status === 'shipped' || $shipment->status === 'delivered' ? 'bg-green-500' : ($shipment->status === 'pending' ? 'bg-yellow-500' : 'bg-blue-500 animate-pulse')); ?> rounded-full"></div>
                            <span class="font-medium text-gray-900">In Transit</span>
                        </div>
                        <span class="text-sm text-gray-500">
                            <?php echo e($shipment->status === 'shipped' || $shipment->status === 'delivered' ? 'Completed' : ($shipment->status === 'pending' ? 'Pending' : 'In Progress')); ?>

                        </span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 <?php echo e($shipment->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300'); ?> rounded-full"></div>
                            <span class="font-medium text-gray-900">Delivered</span>
                        </div>
                        <span class="text-sm text-gray-500">
                            <?php echo e($shipment->status === 'delivered' ? 'Completed' : 'Pending'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Tracking History</h2>
            <div class="tracking-timeline">
                <?php $__currentLoopData = $trackingHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="timeline-item <?php echo e($event['completed'] ? 'completed' : ($index === count($trackingHistory) - 1 ? 'current' : 'pending')); ?>">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-900"><?php echo e($event['status']); ?></h3>
                                <span class="text-sm text-gray-500"><?php echo e($event['date']); ?></span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1"><?php echo e($event['location']); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($event['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center">
            <a href="<?php echo e(route('admin.shipments.show', $shipment)); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                View Shipment Details
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([<?php echo e($currentLocation['coordinates'][0]); ?>, <?php echo e($currentLocation['coordinates'][1]); ?>], 10);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add marker for current location
        const marker = L.marker([<?php echo e($currentLocation['coordinates'][0]); ?>, <?php echo e($currentLocation['coordinates'][1]); ?>]).addTo(map);
        marker.bindPopup('<b><?php echo e($currentLocation['city']); ?></b><br><?php echo e($currentLocation['state']); ?>').openPopup();
        
        // Add circle to show approximate area
        L.circle([<?php echo e($currentLocation['coordinates'][0]); ?>, <?php echo e($currentLocation['coordinates'][1]); ?>], {
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.2,
            radius: 5000
        }).addTo(map);
        
        // Auto-refresh every 30 seconds for real-time updates
        setInterval(function() {
            // In a real implementation, you would fetch updated location from API
            console.log('Checking for shipment updates...');
        }, 30000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\CascadeProjects\invotrack-laravel\resources\views/shipments/track.blade.php ENDPATH**/ ?>