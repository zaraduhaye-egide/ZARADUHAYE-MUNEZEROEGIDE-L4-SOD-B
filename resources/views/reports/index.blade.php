@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Stock Reports</h1>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', date('Y-m-d', strtotime('-30 days'))) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="product" class="form-label">Product</label>
                    <select class="form-select" id="product" name="product">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->ProductCode }}" 
                                    {{ request('product') == $product->ProductCode ? 'selected' : '' }}>
                                {{ $product->ProductName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Stock In</h5>
                    <h2 class="card-text">${{ number_format($totalStockIn, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Stock Out</h5>
                    <h2 class="card-text">${{ number_format($totalStockOut, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Net Stock Value</h5>
                    <h2 class="card-text">${{ number_format($netStockValue, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Stock Movement Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockMovements as $movement)
                            <tr>
                                <td>{{ $movement->Date }}</td>
                                <td>{{ $movement->product->ProductName }}</td>
                                <td>
                                    <span class="badge {{ $movement->type === 'in' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($movement->type) }}
                                    </span>
                                </td>
                                <td>{{ $movement->Quantity }}</td>
                                <td>${{ number_format($movement->UniquePrice, 2) }}</td>
                                <td>${{ number_format($movement->TotalPrice, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $stockMovements->links() }}
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="mb-4">
        <a href="{{ route('reports.export', ['format' => 'pdf'] + request()->all()) }}" 
           class="btn btn-danger">
            Export as PDF
        </a>
        <a href="{{ route('reports.export', ['format' => 'excel'] + request()->all()) }}" 
           class="btn btn-success">
            Export as Excel
        </a>
    </div>
</div>
@endsection 