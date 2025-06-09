@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-start">Stock Out Report</h4>
                    <div class="float-end">
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="fas fa-print"></i> Print Current View
                        </button>
                        <a href="{{ request()->fullUrlWithQuery(['print' => 1]) }}" 
                           class="btn btn-secondary" target="_blank">
                            <i class="fas fa-file-pdf"></i> Print Detailed Report
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <!-- Quick Period Filter -->
                            <div class="col-md-3">
                                <label for="period" class="form-label">Quick Period</label>
                                <select class="form-select" id="period" name="period">
                                    <option value="">Custom Range</option>
                                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="last_week" {{ request('period') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                    <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                    <option value="last_year" {{ request('period') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                                </select>
                            </div>

                            <!-- Custom Date Range -->
                            <div class="col-md-2">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ request('end_date') }}">
                            </div>

                            <!-- Time Range -->
                            <div class="col-md-2">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="start_time" name="start_time"
                                       value="{{ request('start_time') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="end_time" name="end_time"
                                       value="{{ request('end_time') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <!-- Other Filters -->
                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <label for="customer_name" class="form-label">Customer</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                       value="{{ request('customer_name') }}" placeholder="Search customer">
                            </div>

                            <div class="col-md-2">
                                <label for="min_amount" class="form-label">Min Amount</label>
                                <input type="number" class="form-control" id="min_amount" name="min_amount"
                                       value="{{ request('min_amount') }}" step="0.01">
                            </div>

                            <div class="col-md-2">
                                <label for="max_amount" class="form-label">Max Amount</label>
                                <input type="number" class="form-control" id="max_amount" name="max_amount"
                                       value="{{ request('max_amount') }}" step="0.01">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
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
                                    <h5 class="card-title">Total Revenue</h5>
                                    <p class="card-text h3">${{ number_format($totalRevenue, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockOuts as $stockOut)
                                    <tr>
                                        <td>{{ $stockOut->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $stockOut->product->name }}</td>
                                        <td>{{ $stockOut->customer_name }}</td>
                                        <td>{{ number_format($stockOut->quantity) }}</td>
                                        <td>${{ number_format($stockOut->unit_price, 2) }}</td>
                                        <td>${{ number_format($stockOut->total_price, 2) }}</td>
                                        <td>{{ $stockOut->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No stock-out records found.</td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('period');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');

    // Disable/enable custom date inputs based on period selection
    periodSelect.addEventListener('change', function() {
        const isCustom = !this.value;
        startDate.disabled = !isCustom;
        endDate.disabled = !isCustom;
        if (!isCustom) {
            startDate.value = '';
            endDate.value = '';
        }
    });

    // Initial state
    if (periodSelect.value) {
        startDate.disabled = true;
        endDate.disabled = true;
    }
});
</script>
@endpush

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .table {
        width: 100% !important;
    }
}
</style>

@endsection 