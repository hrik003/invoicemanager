<?php
namespace ArnlInvoices\InvoiceManager;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use ArnlInvoices\InvoiceManager\Models\Customer;
use ArnlInvoices\InvoiceManager\Models\InvoiceItem;

class Invoice extends Model {
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'issue_date',
        'due_date',
        'status',
        'sub_total',
        'tax_total',
        'total',
        'currency',
        'notes'
    ];

    public function items() { 
        return $this->hasMany(InvoiceItem::class); 
    }

    public function customer() { 
        return $this->belongsTo(Customer::class); 
    }

    public function user() { 
        return $this->belongsTo(User::class); 
    }
}
