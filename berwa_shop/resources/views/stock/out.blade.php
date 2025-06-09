@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Stock Out Management</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockOutModal">
                        <i class="fas fa-plus"></i> New Stock Out
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Stock Out Records Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
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
                                            <div class="btn-group btn-group-sm">
                                            <a href="{{ route('product-outs.edit', $stockOut->id) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('product-outs.destroy', $stockOut->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No stock out records found</td>
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

<!-- Stock Out Form Modal -->
<div class="modal fade" id="addStockOutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Record Stock Out
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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

                    <!-- Product Selection Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">1. Select Product</h6>
                        </div>
                        <div class="card-body">
                            <select class="form-select form-select-lg @error('product_id') is-invalid @enderror" 
                                    name="product_id" id="product_id" required>
                                <option value="">Choose a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product['id'] }}" 
                                            data-price="{{ $product['unit_price'] }}"
                                            data-formatted-price="{{ $product['formatted_unit_price'] }}"
                                            data-available="{{ $product['available_stock'] }}"
                                            data-name="{{ $product['name'] }}">
                                        {{ $product['name'] }} - ${{ $product['formatted_unit_price'] }} 
                                        ({{ $product['available_stock'] }} in stock)
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock Information Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">2. Stock Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Available Stock</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light" 
                                                   id="available_stock" readonly>
                                            <span class="input-group-text">units</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Unit Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('unit_price') is-invalid @enderror" 
                                                   id="unit_price" name="unit_price" step="0.01" min="0.01" required>
                                            <input type="hidden" id="raw_unit_price" name="raw_unit_price">
                                            @error('unit_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted">Original price: $<span id="original_price"></span></small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quantity to Stock Out</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control form-control-lg @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" min="1" required
                                           placeholder="Enter quantity">
                                    <span class="input-group-text">units</span>
                                </div>
                                <div id="quantityError" class="invalid-feedback"></div>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control bg-light" 
                                           id="total_price" readonly>
                                </div>
                                <div id="priceBreakdown" class="form-text text-success mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">3. Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" 
                                       class="form-control @error('customer_name') is-invalid @enderror" 
                                       name="customer_name" 
                                       value="{{ old('customer_name') }}"
                                       placeholder="Enter customer name">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" 
                                          name="notes" 
                                          rows="2"
                                          placeholder="Add any additional notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Summary -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Transaction Summary</h6>
                        </div>
                        <div class="card-body" id="summaryContent">
                            <p class="text-muted mb-0">Please select a product and enter quantity to see the transaction summary.</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-save"></i> Record Stock Out
                    </button>
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
    const rawUnitPriceInput = document.getElementById('raw_unit_price');
    const totalPriceInput = document.getElementById('total_price');
    const availableStockInput = document.getElementById('available_stock');
    const priceBreakdown = document.getElementById('priceBreakdown');
    const summaryContent = document.getElementById('summaryContent');
    const submitBtn = document.getElementById('submitBtn');
    const originalPriceSpan = document.getElementById('original_price');

    function formatCurrency(value) {
        return parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function updateProductInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            // Get data from the selected option
            const availableStock = parseInt(selectedOption.dataset.available);
            const unitPrice = parseFloat(selectedOption.dataset.price);
            const formattedPrice = selectedOption.dataset.formattedPrice;
            const productName = selectedOption.dataset.name;
            
            // Update displays
            availableStockInput.value = availableStock;
            unitPriceInput.value = unitPrice.toFixed(2); // Set as editable value
            rawUnitPriceInput.value = unitPrice;
            originalPriceSpan.textContent = formattedPrice; // Show original price
            
            // Enable inputs
            quantityInput.disabled = false;
            unitPriceInput.disabled = false;
            quantityInput.value = ''; // Reset quantity when product changes
            
            // Reset calculations
            totalPriceInput.value = '';
            priceBreakdown.innerHTML = '';
            summaryContent.innerHTML = '<p class="text-muted mb-0">Please enter quantity to see the transaction summary.</p>';
            
            // Update submit button state
            submitBtn.disabled = true;
        } else {
            // Reset fields if no product is selected
            availableStockInput.value = '';
            unitPriceInput.value = '';
            rawUnitPriceInput.value = '';
            totalPriceInput.value = '';
            quantityInput.value = '';
            originalPriceSpan.textContent = '';
            quantityInput.disabled = true;
            unitPriceInput.disabled = true;
            submitBtn.disabled = true;
            priceBreakdown.innerHTML = '';
            summaryContent.innerHTML = '<p class="text-muted mb-0">Please select a product and enter quantity to see the transaction summary.</p>';
        }
    }

    function updateCalculations() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) return;

        const quantity = parseInt(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const availableStock = parseInt(selectedOption.dataset.available);
        const productName = selectedOption.dataset.name;
        const originalPrice = parseFloat(selectedOption.dataset.price);
        
        // Calculate total
        const totalPrice = quantity * unitPrice;
        
        // Update displays
        totalPriceInput.value = formatCurrency(totalPrice);
        
        // Update price breakdown
        if (quantity > 0) {
            let priceInfo = `${quantity} units × $${formatCurrency(unitPrice)} per unit`;
            if (unitPrice !== originalPrice) {
                priceInfo += ` (Original: $${formatCurrency(originalPrice)})`;
            }
            priceBreakdown.innerHTML = priceInfo;
            
            // Update summary
            summaryContent.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th>Product:</th>
                            <td>${productName}</td>
                        </tr>
                        <tr>
                            <th>Quantity:</th>
                            <td>${quantity} units</td>
                        </tr>
                        <tr>
                            <th>Unit Price:</th>
                            <td>$${formatCurrency(unitPrice)}</td>
                        </tr>
                        <tr class="table-info">
                            <th>Total Amount:</th>
                            <td>$${formatCurrency(totalPrice)}</td>
                        </tr>
                    </table>
                </div>`;
        } else {
            priceBreakdown.innerHTML = '';
            summaryContent.innerHTML = '<p class="text-muted mb-0">Please enter a valid quantity to see the transaction summary.</p>';
        }
        
        // Validate quantity and update submit button
        if (quantity > availableStock) {
            quantityInput.classList.add('is-invalid');
            document.getElementById('quantityError').textContent = `Cannot exceed available stock (${availableStock} units)`;
            submitBtn.disabled = true;
        } else if (quantity <= 0) {
            quantityInput.classList.add('is-invalid');
            document.getElementById('quantityError').textContent = 'Quantity must be greater than 0';
            submitBtn.disabled = true;
        } else {
            quantityInput.classList.remove('is-invalid');
            document.getElementById('quantityError').textContent = '';
            submitBtn.disabled = false;
        }
    }

    // Event Listeners
    productSelect.addEventListener('change', updateProductInfo);
    quantityInput.addEventListener('input', updateCalculations);
    unitPriceInput.addEventListener('input', updateCalculations);
});
</script>
@endpush

@push('styles')
<style>
.modal-lg {
    max-width: 800px;
}
.card-header {
    padding: 0.75rem 1.25rem;
}
.form-label {
    font-weight: 500;
}
.input-group-text {
    background-color: #f8f9fa;
}
.table-sm th {
    background-color: #f8f9fa;
}
</style>
@endpush
@endsection 