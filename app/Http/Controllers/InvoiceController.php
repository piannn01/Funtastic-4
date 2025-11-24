<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show($invoice_code)
    {
        $order = Order::where('invoice_code', $invoice_code)->firstOrFail();
        return view('invoice.show', compact('order'));
    }

    public function download($invoice_code)
    {
    $order = Order::where('invoice_code', $invoice_code)->firstOrFail();

    $pdf = Pdf::loadView('pdf.invoice', compact('order'))
              ->setPaper('A4', 'portrait');

    return $pdf->download('invoice-'.$invoice_code.'.pdf');
    }

}
