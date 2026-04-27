<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DocumentController;

// Customer Controllers
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\CustomerInvoiceController;
use App\Http\Controllers\Customer\CustomerPaymentController;
use App\Http\Controllers\Customer\CustomerShipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Root route - show portal selection
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes removed - direct access enabled

// Admin Routes (no authentication required)
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/print', [ExportController::class, 'printInvoice'])->name('invoices.print');
    Route::post('/invoices/{invoice}/issue', [InvoiceController::class, 'issue'])->name('invoices.issue');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create/{invoice}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    
    // Shipments
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create/{invoice}', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/{shipment}/track', [ShipmentController::class, 'track'])->name('shipments.track');
    Route::get('/shipments/{shipment}/timeline', [ShipmentController::class, 'timeline'])->name('shipments.timeline');
    Route::get('/shipments/{shipment}/report', [ShipmentController::class, 'report'])->name('shipments.report');
    Route::post('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.updateStatus');
    Route::post('/shipments/{shipment}/pod', [ShipmentController::class, 'uploadPOD'])->name('shipments.uploadPOD');
    Route::post('/shipments/{shipment}/pod/delete', [ShipmentController::class, 'deletePOD'])->name('shipments.deletePOD');
    Route::post('/shipments/{shipment}/arrival', [ShipmentController::class, 'uploadProofOfArrival'])->name('shipments.uploadProofOfArrival');
    Route::post('/shipments/{shipment}/arrival/delete', [ShipmentController::class, 'deleteProofOfArrival'])->name('shipments.deleteProofOfArrival');
    
    // Reports & Exports
    Route::get('/reports/financial', [ExportController::class, 'financialSummary'])->name('reports.financial');
    Route::get('/export/orders', [ExportController::class, 'exportOrdersCSV'])->name('export.orders');
    Route::get('/export/invoices', [ExportController::class, 'exportInvoicesCSV'])->name('export.invoices');
    
    // Documents
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/{document}/send-to-customs', [DocumentController::class, 'sendToCustoms'])->name('documents.sendToCustoms');
});

Route::get('/', function () {
    return view('welcome');
});

// Customer Routes (no authentication required)
Route::prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [CustomerOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Invoices (read-only for customers)
    Route::get('/invoices', [CustomerInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
    
    // Payments
    Route::get('/payments/create/{invoice}', [CustomerPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [CustomerPaymentController::class, 'store'])->name('payments.store');
    
    // Shipments (tracking only for customers)
    Route::get('/shipments', [CustomerShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/{shipment}/track', [CustomerShipmentController::class, 'track'])->name('shipments.track');
    Route::get('/shipments/{shipment}/timeline', [CustomerShipmentController::class, 'timeline'])->name('shipments.timeline');
    Route::get('/shipments/{shipment}/report', [CustomerShipmentController::class, 'report'])->name('shipments.report');
    
    // Documents (view only for customers)
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
});
