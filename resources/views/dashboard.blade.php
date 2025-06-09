@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Summary Cards -->
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
                    <h5 class="card-title">Stock In Today</h5>
                    <h2 class="card-text">{{ $stockInToday }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Stock Out Today</h5>
                    <h2 class="card-text">{{ $stockOutToday }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Value</h5>
                    <h2 class="card-text">${{ number_format($totalValue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                        <a href="{{ route('product-ins.create') }}" class="btn btn-success">Record Stock In</a>
                        <a href="{{ route('product-outs.create') }}" class="btn btn-warning">Record Stock Out</a>
                        <a href="{{ route('reports') }}" class="btn btn-info">Generate Report</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentActivities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                <small>{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $activity->details }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 