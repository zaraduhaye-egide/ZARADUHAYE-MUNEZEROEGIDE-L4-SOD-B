@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Stock In Management</h4>
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addStockInModal">
                        Add New Stock
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
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockIns as $stockIn)
                                    <tr>
                                        <td>{{ $stockIn->id }}</td>
                                        <td>{{ $stockIn->product->name }}</td>
                                        <td>{{ $stockIn->quantity }}</td>
                                        <td>${{ number_format($stockIn->unit_cost, 2) }}</td>
                                        <td>${{ number_format($stockIn->unit_cost * $stockIn->quantity, 2) }}</td>
                                        <td>{{ $stockIn->supplier }}</td>
                                        <td>{{ $stockIn->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('stock.in.edit', $stockIn->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                            <form action="{{ route('stock.in.destroy', $stockIn->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No stock-in records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $stockIns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock In Modal -->
<div class="modal fade" id="addStockInModal" tabindex="-1" aria-labelledby="addStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockInModalLabel">Add New Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stock.in.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required 
                               placeholder="Enter product name">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required 
                               placeholder="Enter product category">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Product Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" required 
                               step="0.01" min="0.01" placeholder="Enter product price">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="unit_cost" class="form-label">Unit Cost ($)</label>
                        <input type="number" class="form-control" id="unit_cost" name="unit_cost" required step="0.01" min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Stock In Modal -->
<div class="modal fade" id="editStockInModal" tabindex="-1" aria-labelledby="editStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockInModalLabel">Edit Stock In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStockInForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_unit_cost" class="form-label">Unit Cost ($)</label>
                        <input type="number" class="form-control" id="edit_unit_cost" name="unit_cost" required step="0.01" min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="edit_supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="edit_supplier" name="supplier">
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button clicks
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const form = document.getElementById('editStockInForm');
            form.action = `/stock/in/${id}`;
            
            document.getElementById('edit_product_name').value = this.dataset.productName;
            document.getElementById('edit_quantity').value = this.dataset.quantity;
            document.getElementById('edit_unit_cost').value = this.dataset.unitCost;
            document.getElementById('edit_supplier').value = this.dataset.supplier;
            document.getElementById('edit_notes').value = this.dataset.notes;
        });
    });
});
</script>
@endpush
@endsection 