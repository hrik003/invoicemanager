<?php

namespace ArnlInvoices\InvoiceManager\Http\Controllers;

use Illuminate\Routing\Controller;
use PDF; // from barryvdh/laravel-dompdf
use Illuminate\Http\Request;
use Models\Invoice;
use Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller {

    public function create() {
        // return view for creating invoice (with customers, tax rates)
        return view('invoicemanager::invoices.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            // 'status' => 'in:draft,issued' etc.
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'user_id' => auth()->id(),
                'customer_id' => $data['customer_id'],
                'issue_date' => $data['issue_date'],
                'due_date' => $data['due_date'] ?? null,
                'status' => $request->input('status', 'draft'),
                'currency' => $request->input('currency', 'INR')
            ]);

            $subTotal = 0; $taxTotal = 0;
            foreach ($data['items'] as $it) {
                $line = $it['quantity'] * $it['unit_price'];
                $taxAmount = 0;
                // calculate taxAmount if tax_rate provided
                if (!empty($it['tax_rate'])) {
                    $taxAmount = round($line * ($it['tax_rate'] / 100), 2);
                }
                $item = new InvoiceItem([
                    'description' => $it['description'],
                    'quantity' => $it['quantity'],
                    'unit_price' => $it['unit_price'],
                    'tax_amount' => $taxAmount,
                    'line_total' => $line + $taxAmount,
                ]);
                $invoice->items()->save($item);

                $subTotal += $line;
                $taxTotal += $taxAmount;
            }

            $invoice->sub_total = $subTotal;
            $invoice->tax_total = $taxTotal;
            $invoice->total = $subTotal + $taxTotal;

            // generate invoice number only when issuing
            if ($invoice->status === 'issued') {
                $invoice->invoice_number = $this->generateInvoiceNumber();
            }

            $invoice->save();

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)->with('success','Invoice created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    protected function generateInvoiceNumber() {
        // simple example - replace with atomic counter in settings table
        $prefix = config('invoices.prefix', 'INV');
        $seq = DB::table('settings')->where('key','invoice_seq')->lockForUpdate()->value('value');
        $seq = intval($seq) + 1;
        DB::table('settings')->where('key','invoice_seq')->update(['value'=>$seq]);
        return sprintf("%s-%s-%05d", $prefix, date('Ym'), $seq);
    }

    public function show(Invoice $invoice) {
        $invoice->load('items','customer');
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice) {
        $invoice->load('items','customer','user');
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        // optionally set paper size / orientation
        return $pdf->download($invoice->invoice_number ?? 'invoice_'.$invoice->id .'.pdf');
    }

    public function emailInvoice(Request $request, Invoice $invoice) {
        $request->validate(['to' => 'required|email']);
        $invoice->load('items','customer','user');
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));

        Mail::send('emails.invoice', ['invoice' => $invoice], function($m) use ($invoice, $pdf, $request) {
            $m->to($request->to)
              ->subject("Invoice ".($invoice->invoice_number ?? $invoice->id))
              ->attachData($pdf->output(), ($invoice->invoice_number ?? 'invoice').'.pdf', [
                  'mime' => 'application/pdf',
              ]);
        });

        return back()->with('success','Invoice emailed.');
    }
}
