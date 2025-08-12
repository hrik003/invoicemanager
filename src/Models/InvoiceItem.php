<?php
namespace ArnlInvoices\InvoiceManager;

use Illuminate\Database\Eloquent\Model;
// use App\Models\User;
// use Models\Customer;
use ArnlInvoices\InvoiceManager\Models\Invoice;

class InvoiceItem extends Model {
    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'tax_rate_id',
        'tax_amount',
        'line_total'
    ];
    
    public function invoice() { 
        return $this->belongsTo(Invoice::class); 
    }
}
