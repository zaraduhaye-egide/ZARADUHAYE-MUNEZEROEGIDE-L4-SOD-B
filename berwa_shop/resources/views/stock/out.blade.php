@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Stock Out Management</h4>
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addStockOutModal">
                        Record Stock Out
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockOuts as $stockOut)
                                    <tr>
                                        <td>{{ $stockOut->id }}</td>
                                        <td>{{ $stockOut->product->name }}</td>
                                        <td>{{ $stockOut->quantity }}</td>
                                        <td>${{ number_format($stockOut->unit_price, 2) }}</td>
                                        <td>${{ number_format($stockOut->total_price, 2) }}</td>
                                        <td>{{ $stockOut->customer_name }}</td>
                                        <td>{{ $stockOut->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('product-outs.edit', $stockOut->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                            <form action="{{ route('product-outs.destroy', $stockOut->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No stock-out records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $stockOuts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Out Modal -->
<div class="modal fade" id="addStockOutModal" tabindex="-1" aria-labelledby="addStockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockOutModalLabel">Record Stock Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('product-outs.store') }}" method="POST" id="stockOutForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                @php
                                    $totalIn = App\Models\ProductIn::where('product_id', $product->id)->sum('quantity');
                                    $totalOut = App\Models\ProductOut::where('product_id', $product->id)->sum('quantity');
                                    $available = $totalIn - $totalOut;
                                @endphp
                                <option value="{{ $product->id }}" 
                                        data-available="{{ $available }}"
                                        data-price="{{ $product->price }}">
                                    {{ $product->name }} (Available: {{ $available }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="available_stock" class="form-label">Available Stock</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="available_stock" readonly>
                            <span class="input-group-text">units</span>
                        </div>
                        <small class="text-muted">Current available quantity in stock</small>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity to Stock Out</label>
                        <div class="input-group has-validation">
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   required min="1" value="{{ old('quantity') }}">
                            <span class="input-group-text">units</span>
                            <div id="quantityError" class="invalid-feedback"></div>
                        </div>
                        <div id="quantityHelp" class="form-text">Enter the quantity you want to stock out</div>
                    </div>

                    <div class="mb-3">
                        <label for="unit_price" class="form-label">Unit Price ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="unit_price" name="unit_price" 
                                   required step="0.01" min="0.01" value="{{ old('unit_price') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="total_price" class="form-label">Total Price ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control bg-light" id="total_price" name="total_price" readonly>
                        </div>
                        <small class="text-muted">Automatically calculated: Quantity × Unit Price</small>
                    </div>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               value="{{ old('customer_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save</button>
                </div>
            </form>
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
    const quantityHelp = document.getElementById('quantityHelp');
    const submitBtn = document.getElementById('submitBtn');
    const stockOutForm = document.getElementById('stockOutForm');

    function updateAvailableStock() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption.value) {
            const availableStock = parseInt(selectedOption.dataset.available);
            availableStockInput.value = availableStock;
            
            // Set default unit price from product price if not already set
            if (!unitPriceInput.value) {
                unitPriceInput.value = selectedOption.dataset.price || '';
            }
            
            // Reset quantity and recalculate total
            quantityInput.value = '';
            calculateTotal();
            validateQuantity();

            // Update help text based on available stock
            if (availableStock <= 0) {
                quantityHelp.className = 'form-text text-danger';
                quantityHelp.textContent = 'No stock available for this product';
                quantityInput.disabled = true;
                submitBtn.disabled = true;
            } else {
                quantityHelp.className = 'form-text text-muted';
                quantityHelp.textContent = `Enter quantity (max: ${availableStock} units)`;
                quantityInput.disabled = false;
                submitBtn.disabled = false;
            }
        } else {
            availableStockInput.value = '';
            unitPriceInput.value = '';
            totalPriceInput.value = '';
            quantityHelp.className = 'form-text text-muted';
            quantityHelp.textContent = 'Select a product first';
            quantityInput.disabled = true;
        }
    }

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        totalPriceInput.value = total.toFixed(2);
    }

    function validateQuantity() {
        const quantity = parseInt(quantityInput.value) || 0;
        const availableStock = parseInt(availableStockInput.value) || 0;
        
        if (availableStock <= 0) {
            quantityInput.classList.add('is-invalid');
            quantityError.textContent = 'Error: No stock available for this product';
            submitBtn.disabled = true;
            return false;
        } else if (quantity > availableStock) {
            quantityInput.classList.add('is-invalid');
            quantityError.textContent = `Error: Cannot stock out more than available stock (${availableStock} units)`;
            submitBtn.disabled = true;
            return false;
        } else if (quantity <= 0) {
            quantityInput.classList.add('is-invalid');
            quantityError.textContent = 'Error: Quantity must be greater than 0';
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
    productSelect.addEventListener('change', updateAvailableStock);
    
    quantityInput.addEventListener('input', function() {
        validateQuantity();
        calculateTotal();
    });
    
    unitPriceInput.addEventListener('input', calculateTotal);
    
    stockOutForm.addEventListener('submit', function(e) {
        if (!validateQuantity()) {
            e.preventDefault();
            return false;
        }
        return true;
    });

    // Initial setup
    updateAvailableStock();
});
</script>
@endpush
@endsection 