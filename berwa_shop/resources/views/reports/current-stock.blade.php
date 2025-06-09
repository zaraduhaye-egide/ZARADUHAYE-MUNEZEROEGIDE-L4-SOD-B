@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Current Stock Report</h4>
                    <div class="float-end">
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="stock_status" class="form-label">Stock Status</label>
                                    <select class="form-select" id="stock_status" name="stock_status">
                                        <option value="">All Status</option>
                                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Search products...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text h3">{{ number_format($totalProducts) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Stock Value</h5>
                                    <p class="card-text h3">${{ number_format($totalStockValue, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Low Stock Items</h5>
                                    <p class="card-text h3">{{ number_format($lowStockCount) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Out of Stock</h5>
                                    <p class="card-text h3">{{ number_format($outOfStockCount) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>{{ number_format($product->current_stock) }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>${{ number_format($product->current_stock * $product->price, 2) }}</td>
                                        <td>
                                            @if($product->current_stock <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($product->current_stock <= 10)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .badge {
            border: 1px solid #000 !important;
        }
    }
</style>
@endpush
@endsection 