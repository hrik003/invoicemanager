<?php
namespace ArnlInvoices\InvoiceManager;

use Illuminate\Database\Eloquent\Model;
// use App\Models\User;
// use Models\Customer;
use ArnlInvoices\InvoiceManager\Models\Invoice;

class Customer extends Model {
    protected $fillable = [
        'name',
        'email',
        'phone',
        'billing_address',
        'gst_number'
    ];
    
    public function invoices() { 
        return $this->hasMany(Invoice::class); 
    }
}
