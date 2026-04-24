<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerPaymentController extends Controller
{
    /**
     * Show payment creation form
     */
    public function create(Invoice $invoice)
    {
        $customer = Auth::user();
        
        // Access control for authenticated users
        if ($customer) {
            if ($invoice->order->customer_id !== $customer->id && $invoice->order->customer_email !== $customer->email) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // Access control for non-authenticated users - check session
            $customerEmail = session('customer_email');
            $customerPhone = session('customer_phone');
            
            if ($invoice->order->customer_email !== $customerEmail && $invoice->order->customer_phone !== $customerPhone) {
                abort(403, 'Unauthorized access - Please provide your email or phone to make payments');
            }
        }

        // Check if invoice is already paid
        if ($invoice->status === 'paid' || $invoice->status === 'closed') {
            return redirect()->route('customer.invoices.show', $invoice)
                ->with('error', 'This invoice has already been paid.');
        }

        return view('customer.payments.create', compact('invoice'));
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,bank_transfer,credit_card,other',
            'payment_date' => 'required|date',
            'transaction_reference' => 'nullable|string|max:255',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $customer = Auth::user();
        
        // Access control
        if ($customer) {
            if ($invoice->order->customer_id !== $customer->id && $invoice->order->customer_email !== $customer->email) {
                abort(403, 'Unauthorized access');
            }
        } else {
            $customerEmail = session('customer_email');
            $customerPhone = session('customer_phone');
            
            if ($invoice->order->customer_email !== $customerEmail && $invoice->order->customer_phone !== $customerPhone) {
                abort(403, 'Unauthorized access');
            }
        }

        // Check if payment amount exceeds invoice amount
        if ($request->amount > $invoice->amount) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed invoice amount.']);
        }

        // Handle payment proof upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'payment_proof_' . time() . '_' . $file->getClientOriginalName();
            $paymentProofPath = $file->storeAs('payment_proofs', $filename, 'public');
        }

        // Create payment with customer identifiers
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'transaction_reference' => $request->transaction_reference,
            'payment_proof_file_path' => $paymentProofPath,
            'customer_email' => $customer?->email ?? $invoice->order->customer_email,
            'customer_phone' => $customer?->profile?->phone ?? $invoice->order->customer_phone,
            'notes' => $request->notes,
            'status' => 'pending', // Pending admin verification
        ]);

        // Check if invoice is now fully paid (only verified payments count)
        $verifiedTotal = $invoice->payments()->where('status', 'verified')->sum('amount');
        if ($verifiedTotal >= $invoice->amount) {
            $invoice->update([
                'status' => 'paid',
                'paid_date' => now(),
            ]);
        }

        return redirect()->route('customer.invoices.show', $invoice)
            ->with('success', 'Payment submitted successfully! Payment proof uploaded. Waiting for admin verification.');
    }
}
