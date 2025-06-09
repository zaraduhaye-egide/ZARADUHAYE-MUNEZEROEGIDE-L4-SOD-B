@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Stock In</h5>
                    <h2 class="card-text">{{ $totalStockIn }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Stock Out</h5>
                    <h2 class="card-text">{{ $totalStockOut }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Current Stock</h5>
                    <h2 class="card-text">{{ $currentStock }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Stock In Management</h5>
                    <p class="card-text">Add new products to inventory or manage existing stock entries.</p>
                    <a href="{{ route('stock.in.index') }}" class="btn btn-primary">
                        <i class="fas fa-box"></i> Manage Stock In
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Stock Out Management</h5>
                    <p class="card-text">Record product sales and manage outgoing inventory.</p>
                    <a href="{{ route('product-outs.index') }}" class="btn btn-danger">
                        <i class="fas fa-shipping-fast"></i> Manage Stock Out
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Recent Stock Ins
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recentStockIns as $stockIn)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $stockIn->product->name }}</h6>
                                    <small>{{ $stockIn->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Quantity: {{ $stockIn->quantity }}</p>
                            </div>
                        @empty
                            <div class="list-group-item">No recent stock ins</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Recent Stock Outs
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recentStockOuts as $stockOut)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $stockOut->product->name }}</h6>
                                    <small>{{ $stockOut->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Quantity: {{ $stockOut->quantity }}</p>
                            </div>
                        @empty
                            <div class="list-group-item">No recent stock outs</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 