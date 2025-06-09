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

                    <!-- Current Stock Overview -->
                    <div class="mb-4">
                        <h5>Available Stock Overview</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Available Stock</th>
                                        <th>Unit Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                @if($product->available_stock > 0)
                                                    <span class="badge bg-success">{{ $product->available_stock }} units</span>
                                                @else
                                                    <span class="badge bg-danger">Out of stock</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Out Records -->
                    <h5>Stock Out Records</h5>
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
                                <option value="{{ $product->id }}" 
                                        data-available="{{ $product->available_stock }}"
                                        data-price="{{ $product->price }}">
                                    {{ $product->name }} (Available: {{ $product->available_stock }} units) - ${{ number_format($product->price, 2) }} each
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

                    <div class="form-group mb-4">
                        <label for="quantity" class="form-label h6 mb-2">Quantity to Stock Out</label>
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control form-control-lg @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity') }}"
                                   style="font-size: 1.1rem; padding: 12px 15px;"
                                   placeholder="Enter quantity to stock out">
                            <span class="input-group-text" style="font-size: 1.1rem;">units</span>
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div id="quantityError" class="text-danger mt-1"></div>
                        <small id="quantityHelp" class="form-text text-muted">
                            Enter the number of units you want to stock out
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="unit_price" class="form-label h6 mb-2">Unit Price ($)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="font-size: 1.1rem;">$</span>
                            <input type="text" 
                                   class="form-control form-control-lg bg-light @error('unit_price') is-invalid @enderror" 
                                   id="unit_price" 
                                   name="unit_price" 
                                   style="font-size: 1.1rem; padding: 12px 15px;"
                                   readonly>
                        </div>
                        <small class="text-muted">Price per unit (automatically set from product)</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="total_price" class="form-label h6 mb-2">Total Price ($)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="font-size: 1.1rem;">$</span>
                            <input type="text" 
                                   class="form-control form-control-lg bg-light" 
                                   id="total_price" 
                                   style="font-size: 1.1rem; padding: 12px 15px;"
                                   readonly>
                            <span class="input-group-text" style="font-size: 1.1rem;">Total</span>
                        </div>
                        <div id="totalHelp" class="form-text text-success mt-1"></div>
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

                    <!-- Live Preview Section -->
                    <div class="mb-3 border-top pt-3">
                        <label class="form-label">Transaction Summary</label>
                        <div class="alert alert-info" id="previewText">
                            Please select a product and enter quantity to see calculation.
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Confirm Stock Out</button>
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
    const quantityError = document.getElementById('quantityError');
    const quantityHelp = document.getElementById('quantityHelp');
    const availableStockInput = document.getElementById('available_stock');

    // Handle quantity input
    quantityInput.addEventListener('keypress', function(e) {
        // Allow only numbers (0-9)
        if (e.key < '0' || e.key > '9') {
            e.preventDefault();
        }
    });

    quantityInput.addEventListener('input', function() {
        // Remove any non-numeric characters
        this.value = this.value.replace(/\D/g, '');
        
        // Validate quantity
        const quantity = parseInt(this.value) || 0;
        const availableStock = parseInt(availableStockInput.value) || 0;

        if (quantity <= 0) {
            quantityError.textContent = 'Quantity must be greater than 0';
            quantityError.style.display = 'block';
        } else if (quantity > availableStock) {
            quantityError.textContent = `Cannot stock out more than available stock (${availableStock} units)`;
            quantityError.style.display = 'block';
        } else {
            quantityError.textContent = '';
            quantityError.style.display = 'none';
        }

        updateTotals();
    });

    // Format currency
    function formatCurrency(value) {
        return parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Update calculations
    function updateTotals() {
        const quantity = parseInt(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        
        // Format and display total
        totalPriceInput.value = formatCurrency(total);
        
        // Show calculation breakdown
        const totalHelp = document.getElementById('totalHelp');
        if (quantity > 0 && unitPrice > 0) {
            totalHelp.innerHTML = `
                <span class="text-success">
                    ${quantity} units × $${formatCurrency(unitPrice)} = $${formatCurrency(total)}
                </span>
            `;
        } else {
            totalHelp.innerHTML = '';
        }
    }

    // Handle product selection
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const availableStock = parseInt(selectedOption.dataset.available) || 0;
            availableStockInput.value = availableStock;
            unitPriceInput.value = formatCurrency(selectedOption.dataset.price || 0);
            
            // Reset and focus quantity
            quantityInput.value = '';
            quantityInput.focus();
            
            // Update help text
            quantityHelp.textContent = `Available stock: ${availableStock} units`;
        } else {
            availableStockInput.value = '';
            unitPriceInput.value = '';
            quantityInput.value = '';
            quantityHelp.textContent = 'Select a product first';
        }
        updateTotals();
    });

    // Form validation
    const stockOutForm = document.getElementById('stockOutForm');
    stockOutForm.addEventListener('submit', function(e) {
        const quantity = parseInt(quantityInput.value) || 0;
        const availableStock = parseInt(availableStockInput.value) || 0;

        if (quantity <= 0) {
            e.preventDefault();
            quantityError.textContent = 'Quantity must be greater than 0';
            quantityError.style.display = 'block';
        } else if (quantity > availableStock) {
            e.preventDefault();
            quantityError.textContent = `Cannot stock out more than available stock (${availableStock} units)`;
            quantityError.style.display = 'block';
        }
    });

    // Initialize
    updateTotals();
});
</script>
@endpush
@endsection 