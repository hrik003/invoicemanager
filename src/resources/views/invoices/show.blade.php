@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invoice {{ $invoice->invoice_number ?? 'DRAFT-'.$invoice->id }}</h1>
    <div>Customer: {{ $invoice->customer->name }}</div>
    <div>Date: {{ $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : '' }}</div>
    <div>Total: {{ number_format($invoice->total,2) }}</div>
    <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-sm btn-secondary">Download PDF</a>

    <form method="POST" action="{{ route('invoices.email', $invoice) }}" style="display:inline-block;">
        @csrf
        <input name="to" type="email" placeholder="email to" required>
        <button class="btn btn-sm btn-primary">Email Invoice</button>
    </form>
</div>
@endsection
