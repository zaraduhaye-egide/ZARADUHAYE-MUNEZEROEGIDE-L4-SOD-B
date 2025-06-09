@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Stock In Record</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('stock.in.update', $stockIn->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" 
                                   value="{{ old('product_name', $stockIn->product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="{{ old('quantity', $stockIn->quantity) }}" required min="1">
                        </div>

                        <div class="mb-3">
                            <label for="unit_cost" class="form-label">Unit Cost ($)</label>
                            <input type="number" class="form-control" id="unit_cost" name="unit_cost" 
                                   value="{{ old('unit_cost', $stockIn->unit_cost) }}" required step="0.01" min="0.01">
                        </div>

                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier" name="supplier" 
                                   value="{{ old('supplier', $stockIn->supplier) }}">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" 
                                      rows="3">{{ old('notes', $stockIn->notes) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('stock.in.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 