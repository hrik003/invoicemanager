<?php
namespace ArnlInvoices\InvoiceManager;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = ['key', 'user_id', 'product', 'expires_at', 'active', 'meta'];

    protected $casts = [
        'meta' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return $this->active && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
