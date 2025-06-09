@extends('layouts.app')

@section('title', 'Record Stock In')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Record Stock In</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('product-ins.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="ProductCode" class="form-label">Product</label>
                            <select class="form-select @error('ProductCode') is-invalid @enderror" 
                                    id="ProductCode" name="ProductCode" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->ProductCode }}" 
                                            {{ old('ProductCode') == $product->ProductCode ? 'selected' : '' }}>
                                        {{ $product->ProductCode }} - {{ $product->ProductName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ProductCode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('Date') is-invalid @enderror" 
                                   id="Date" name="Date" value="{{ old('Date', date('Y-m-d')) }}" required>
                            @error('Date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control @error('Quantity') is-invalid @enderror" 
                                   id="Quantity" name="Quantity" value="{{ old('Quantity') }}" 
                                   min="1" required>
                            @error('Quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="UniquePrice" class="form-label">Unit Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('UniquePrice') is-invalid @enderror" 
                                       id="UniquePrice" name="UniquePrice" value="{{ old('UniquePrice') }}" 
                                       min="0.01" required>
                                @error('UniquePrice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="TotalPrice" class="form-label">Total Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="TotalPrice" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Record Stock In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calculate total price automatically
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('Quantity');
        const uniquePriceInput = document.getElementById('UniquePrice');
        const totalPriceInput = document.getElementById('TotalPrice');

        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const uniquePrice = parseFloat(uniquePriceInput.value) || 0;
            const total = quantity * uniquePrice;
            totalPriceInput.value = total.toFixed(2);
        }

        quantityInput.addEventListener('input', calculateTotal);
        uniquePriceInput.addEventListener('input', calculateTotal);
    });
</script>
@endpush
@endsection 