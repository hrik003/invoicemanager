<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { display:flex; justify-content:space-between; align-items:center; }
    .items table { width:100%; border-collapse: collapse; margin-top:15px;}
    .items th, .items td { border:1px solid #ccc; padding:6px; text-align:left;}
    .right { text-align:right; }
  </style>
</head>
<body>
  <div class="header">
    <div>
      <h2>{{ config('app.name') }}</h2>
      <div>Address line 1</div>
    </div>
    <div>
      <strong>Invoice</strong><br>
      <div>No: {{ $invoice->invoice_number ?? 'DRAFT-'.$invoice->id }}</div>
      <div>Date: {{ $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : '' }}</div>
      <div>Due: {{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}</div>
    </div>
  </div>

  <hr>

  <div>
    <strong>Bill To:</strong><br>
    {{ $invoice->customer->name }}<br>
    {!! nl2br(e($invoice->customer->billing_address)) !!}
    <div>Email: {{ $invoice->customer->email }}</div>
  </div>

  <div class="items">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Description</th><th>Qty</th><th>Unit</th><th class="right">Tax</th><th class="right">Line Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoice->items as $i => $item)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $item->description }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ number_format($item->unit_price,2) }}</td>
          <td class="right">{{ number_format($item->tax_amount,2) }}</td>
          <td class="right">{{ number_format($item->line_total,2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="right">Subtotal</td>
          <td class="right">{{ number_format($invoice->sub_total,2) }}</td>
        </tr>
        <tr>
          <td colspan="5" class="right">Tax</td>
          <td class="right">{{ number_format($invoice->tax_total,2) }}</td>
        </tr>
        <tr>
          <td colspan="5" class="right"><strong>Total</strong></td>
          <td class="right"><strong>{{ number_format($invoice->total,2) }}</strong></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div style="margin-top:20px;">
    <strong>Notes:</strong><br>
    {!! nl2br(e($invoice->notes)) !!}
  </div>
</body>
</html>
