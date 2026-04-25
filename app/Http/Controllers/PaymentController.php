<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with(['invoice.order'])
            ->latest()
            ->paginate(10);
        
        return view('invotrack.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Invoice $invoice)
    {
        if ($invoice->status !== 'issued') {
            return back()->with('error', 'Payments can only be created for issued invoices.');
        }

        return view('invotrack.payments.create', compact('invoice'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,other',
            'payment_date' => 'required|date',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        
        if ($invoice->isFullyPaid()) {
            return back()->with('error', 'This invoice is already fully paid.');
        }

        $payment = Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully. Please verify the payment to update invoice status.');
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment)
    {
        $payment->verify();
        
        return back()->with('success', 'Payment verified and invoice updated.');
    }
}
