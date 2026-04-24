<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with(['order', 'payments', 'shipment'])
            ->latest()
            ->paginate(10);
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['order', 'payments', 'shipment']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Issue the invoice.
     */
    public function issue(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be issued.');
        }

        $invoice->update([
            'status' => 'issued',
            'issue_date' => now(),
        ]);

        return back()->with('success', 'Invoice issued successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Business rule validation: Can only delete draft invoices without payments
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Cannot delete invoice: Invoice is already ' . $invoice->status);
        }

        if ($invoice->payments->count() > 0) {
            return back()->with('error', 'Cannot delete invoice: Payments already recorded');
        }

        try {
            $invoice->delete();
            return redirect()->route('admin.invoices.index')
                ->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }
}
