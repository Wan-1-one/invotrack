<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        $document->load('order.invoice.shipment');
        
        return view('invotrack.documents.show', compact('document'));
    }

    /**
     * Send document to customs (admin only).
     */
    public function sendToCustoms(Request $request, Document $document)
    {
        // Only allow pending documents to be sent
        if ($document->status !== 'pending') {
            return back()->with('error', 'Document has already been processed.');
        }

        // Mark document as approved
        $document->markAsApproved();

        return back()->with('success', 'Document Approved by Customs');
    }
}
