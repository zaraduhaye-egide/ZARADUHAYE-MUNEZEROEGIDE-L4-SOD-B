@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Inventory Analytics</h4>
                    <div class="float-end">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ request('start_date', now()->subMonths(1)->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Update Analytics</button>
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
                                    <h5 class="card-title">Total Revenue</h5>
                                    <p class="card-text h3">${{ number_format($totalRevenue, 2) }}</p>
                                    <small>{{ $revenueGrowth }}% from previous period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Transactions</h5>
                                    <p class="card-text h3">{{ number_format($totalTransactions) }}</p>
                                    <small>{{ $transactionGrowth }}% from previous period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Average Order Value</h5>
                                    <p class="card-text h3">${{ number_format($averageOrderValue, 2) }}</p>
                                    <small>{{ $aovGrowth }}% from previous period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Turnover Rate</h5>
                                    <p class="card-text h3">{{ number_format($stockTurnoverRate, 2) }}x</p>
                                    <small>{{ $turnoverGrowth }}% from previous period</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Daily Revenue Trend</h5>
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Top Selling Products</h5>
                                    <canvas id="productsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Performing Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Units Sold</th>
                                            <th>Revenue</th>
                                            <th>Profit Margin</th>
                                            <th>Stock Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ number_format($product->units_sold) }}</td>
                                                <td>${{ number_format($product->revenue, 2) }}</td>
                                                <td>{{ number_format($product->profit_margin, 1) }}%</td>
                                                <td>
                                                    @if($product->current_stock <= 0)
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    @elseif($product->current_stock <= 10)
                                                        <span class="badge bg-warning">Low Stock</span>
                                                    @else
                                                        <span class="badge bg-success">In Stock</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueChart->labels) !!},
            datasets: [{
                label: 'Daily Revenue',
                data: {!! json_encode($revenueChart->data) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });

    // Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productsChart->labels) !!},
            datasets: [{
                data: {!! json_encode($productsChart->data) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
});
</script>
@endpush

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