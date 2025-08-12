<?php
namespace ArnlInvoices\InvoiceManager\Services;

use Illuminate\Support\Str;
// namespace App\Services;
use ArnlInvoices\InvoiceManager\Models\Invoice;
use ArnlInvoices\InvoiceManager\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceService {

    public function createInvoice(array $data): Invoice {
        
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::create([
                'user_id' => auth()->id(),
                'customer_id' => $data['customer_id'],
                'issue_date' => $data['issue_date'],
                'due_date' => $data['due_date'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'currency' => $data['currency'] ?? 'INR'
            ]);

            $subTotal = 0; $taxTotal = 0;
            foreach ($data['items'] as $itemData) {
                $line = $itemData['quantity'] * $itemData['unit_price'];
                $taxAmount = !empty($itemData['tax_rate']) 
                    ? round($line * ($itemData['tax_rate'] / 100), 2) : 0;
                
                $invoice->items()->create([
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'tax_amount' => $taxAmount,
                    'line_total' => $line + $taxAmount
                ]);

                $subTotal += $line;
                $taxTotal += $taxAmount;
            }

            $invoice->update([
                'sub_total' => $subTotal,
                'tax_total' => $taxTotal,
                'total' => $subTotal + $taxTotal,
                'invoice_number' => $invoice->status === 'issued' ? $this->generateInvoiceNumber() : null
            ]);

            return $invoice;
        });
    }

    protected function generateInvoiceNumber() {
        $prefix = DB::table('settings')->where('key','invoice_prefix')->value('value') ?? 'INV';
        $seq = DB::table('settings')->where('key','invoice_seq')->lockForUpdate()->value('value');
        $seq = intval($seq) + 1;
        DB::table('settings')->where('key','invoice_seq')->update(['value'=>$seq]);
        return sprintf("%s-%s-%05d", $prefix, date('Ym'), $seq);
    }
}
