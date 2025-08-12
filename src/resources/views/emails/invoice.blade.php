<p>Dear {{ $invoice->customer->name }},</p>
<p>Please find attached the invoice <strong>{{ $invoice->invoice_number ?? 'DRAFT-'.$invoice->id }}</strong>.</p>
<p>Regards,<br>{{ config('app.name') }}</p>
