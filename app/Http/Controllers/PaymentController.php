<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        
        if ($invoice->isFullyPaid()) {
            return back()->with('error', 'This invoice is already fully paid.');
        }

        try {
            $payment = Payment::create([
                'invoice_id' => $validated['invoice_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'transaction_reference' => $validated['reference_number'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            // Update invoice status to partially_paid
            $invoice->update(['status' => 'partially_paid']);

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment recorded successfully. Please verify the payment to update invoice status.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to process payment. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment)
    {
        try {
            DB::beginTransaction();

            // Update payment status to verified
            $payment->status = 'verified';
            $payment->save();

            $invoice = $payment->invoice;

            // Check if invoice is fully paid with verified payments
            $totalVerified = $invoice->payments()->where('status', 'verified')->sum('amount');
            if ($totalVerified >= $invoice->amount) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_date' => now(),
                ]);

                // Update order status
                if ($invoice->order) {
                    $invoice->order->update(['status' => 'confirmed']);
                }
            }

            DB::commit();

            return back()->with('success', 'Payment verified and invoice updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to verify payment. Error: ' . $e->getMessage());
        }
    }
}
