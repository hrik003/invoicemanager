@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Invoice</h1>
    <form method="POST" action="{{ route('invoices.store') }}">
      @csrf
      <div class="mb-3">
        <label>Customer</label>
        <select name="customer_id" class="form-control" required>
          @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label>Issue Date</label>
        <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
      </div>
      <div id="items">
        <h4>Items</h4>
        <div class="item-row">
          <input name="items[0][description]" placeholder="Description" required>
          <input name="items[0][quantity]" type="number" step="0.01" value="1" required>
          <input name="items[0][unit_price]" type="number" step="0.01" value="0" required>
          <input name="items[0][tax_rate]" type="number" step="0.01" value="0">
        </div>
      </div>
      <button type="button" onclick="addItem()">Add Item</button>
      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="draft">Draft</option>
          <option value="issued">Issued</option>
        </select>
      </div>
      <button class="btn btn-primary">Save</button>
    </form>
</div>

<script>
function addItem(){
    const idx = document.querySelectorAll('.item-row').length;
    const div = document.createElement('div');
    div.className = 'item-row';
    div.innerHTML = `<input name="items[${idx}][description]" placeholder="Description" required>
                     <input name="items[${idx}][quantity]" type="number" step="0.01" value="1" required>
                     <input name="items[${idx}][unit_price]" type="number" step="0.01" value="0" required>
                     <input name="items[${idx}][tax_rate]" type="number" step="0.01" value="0">`;
    document.getElementById('items').appendChild(div);
}
</script>
@endsection
