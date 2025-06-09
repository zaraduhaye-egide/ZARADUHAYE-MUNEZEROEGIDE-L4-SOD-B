@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Stock In Report</h4>
                    <a href="{{ request()->fullUrlWithQuery(['print' => 1]) }}" 
                       class="btn btn-primary float-end" target="_blank">
                        Print Report
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select class="form-select" id="product_id" name="product_id">
                                        <option value="">All Products</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <a href="{{ route('reports.stock-in') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Total Quantity</h5>
                                    <p class="card-text h3">{{ number_format($totalQuantity) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Total Cost</h5>
                                    <p class="card-text h3">${{ number_format($totalCost, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockIns as $stockIn)
                                    <tr>
                                        <td>{{ $stockIn->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $stockIn->product->name }}</td>
                                        <td>{{ number_format($stockIn->quantity) }}</td>
                                        <td>${{ number_format($stockIn->unit_cost, 2) }}</td>
                                        <td>${{ number_format($stockIn->quantity * $stockIn->unit_cost, 2) }}</td>
                                        <td>{{ $stockIn->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No stock-in records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 