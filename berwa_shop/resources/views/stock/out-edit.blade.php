@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Stock Out Record</h4>
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

                    <form action="{{ route('product-outs.update', $stockOut->id) }}" method="POST" id="stockOutForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                @foreach($products as $product)
                                    @php
                                        $totalIn = App\Models\ProductIn::where('product_id', $product->id)->sum('quantity');
                                        $totalOut = App\Models\ProductOut::where('product_id', $product->id)
                                            ->where('id', '!=', $stockOut->id)
                                            ->sum('quantity');
                                        $available = $totalIn - $totalOut;
                                    @endphp
                                    <option value="{{ $product->id }}" 
                                            data-available="{{ $available }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            {{ $product->id == $stockOut->product_id ? 'selected' : '' }}>
                                        {{ $product->name }} (Available: {{ $available }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="available_stock" class="form-label">Available Stock</label>
                            <input type="text" class="form-control" id="available_stock" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity to Stock Out</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   required min="1" value="{{ old('quantity', $stockOut->quantity) }}">
                            <div id="quantityError" class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="unit_price" class="form-label">Unit Price ($)</label>
                            <input type="number" class="form-control" id="unit_price" name="unit_price" 
                                   required step="0.01" min="0.01" 
                                   value="{{ old('unit_price', $stockOut->unit_price) }}">
                        </div>

                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total Price ($)</label>
                            <input type="text" class="form-control" id="total_price" readonly 
                                   value="{{ $stockOut->total_price }}">
                        </div>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                   value="{{ old('customer_name', $stockOut->customer_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" 
                                      rows="3">{{ old('notes', $stockOut->notes) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product-outs.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Update Stock Out</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalPriceInput = document.getElementById('total_price');
    const availableStockInput = document.getElementById('available_stock');
    const quantityError = document.getElementById('quantityError');
    const submitBtn = document.getElementById('submitBtn');
    const stockOutForm = document.getElementById('stockOutForm');

    function updateAvailableStock() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption.value) {
            const availableStock = selectedOption.dataset.available;
            availableStockInput.value = availableStock;
            
            // Set default unit price from product price if not already set
            if (!unitPriceInput.value) {
                unitPriceInput.value = selectedOption.dataset.price || '';
            }
            
            validateQuantity();
        } else {
            availableStockInput.value = '';
            totalPriceInput.value = '';
        }
    }

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        totalPriceInput.value = (quantity * unitPrice).toFixed(2);
    }

    function validateQuantity() {
        const quantity = parseInt(quantityInput.value) || 0;
        const availableStock = parseInt(availableStockInput.value) || 0;
        
        if (quantity > availableStock) {
            quantityInput.classList.add('is-invalid');
            quantityError.textContent = `Cannot stock out more than available stock (${availableStock})`;
            submitBtn.disabled = true;
            return false;
        } else if (quantity <= 0) {
            quantityInput.classList.add('is-invalid');
            quantityError.textContent = 'Quantity must be greater than 0';
            submitBtn.disabled = true;
            return false;
        } else {
            quantityInput.classList.remove('is-invalid');
            quantityError.textContent = '';
            submitBtn.disabled = false;
            return true;
        }
    }

    // Event listeners
    productSelect.addEventListener('change', function() {
        updateAvailableStock();
        calculateTotal();
    });
    
    quantityInput.addEventListener('input', function() {
        validateQuantity();
        calculateTotal();
    });
    
    unitPriceInput.addEventListener('input', calculateTotal);
    
    stockOutForm.addEventListener('submit', function(e) {
        if (!validateQuantity()) {
            e.preventDefault();
        }
    });

    // Initial setup
    updateAvailableStock();
    calculateTotal();
});
</script>
@endpush
@endsection 