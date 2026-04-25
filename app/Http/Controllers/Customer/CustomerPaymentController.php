<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    /**
     * Show the payment creation form
     */
    public function create(Invoice $invoice)
    {
        // Ensure customer can only pay their own invoices
        if (Auth::check() && $invoice->order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if invoice is already paid
        if ($invoice->status === 'paid') {
            return back()->with('error', 'This invoice is already paid.');
        }

        return view('customer.payments.create', compact('invoice'));
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'payment_method' => ['required', 'string', 'in:bank_transfer,credit_card,cash,online_banking'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        // Ensure customer can only pay their own invoices
        if (Auth::check() && $invoice->order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if payment amount exceeds invoice amount
        $totalPaid = $invoice->payments()->sum('amount');
        if (($totalPaid + $request->amount) > $invoice->amount) {
            return back()->withInput()
                ->with('error', 'Payment amount exceeds invoice total.');
        }

        try {
            DB::beginTransaction();

            // Create payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            // Update invoice status if fully paid
            $newTotalPaid = $totalPaid + $request->amount;
            if ($newTotalPaid >= $invoice->amount) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_date' => $request->payment_date,
                ]);

                // Update order status
                $invoice->order->update(['status' => 'confirmed']);
            } else {
                $invoice->update(['status' => 'partially_paid']);
            }

            DB::commit();

            return redirect()->route('customer.invoices.show', $invoice)
                ->with('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to process payment. Please try again.');
        }
    }
}
