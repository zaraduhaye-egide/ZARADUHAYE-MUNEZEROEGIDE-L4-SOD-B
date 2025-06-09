<!DOCTYPE html>
<html>
<head>
    <title>Stock Out Report - {{ date('Y-m-d') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            padding: 2rem;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .summary {
            margin: 1rem 0;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        .filter-info {
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #666;
        }
        .table th {
            background-color: #f8f9fa !important;
        }
        .footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BERWA SHOP</h2>
        <h3>Stock Out Report</h3>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="filter-info">
        <strong>Report Filters:</strong>
        <ul>
            @if($request->period)
                <li>Period: {{ ucwords(str_replace('_', ' ', $request->period)) }}</li>
            @endif
            @if($request->start_date)
                <li>Date Range: {{ $request->start_date }} to {{ $request->end_date ?? 'Present' }}</li>
            @endif
            @if($request->start_time)
                <li>Time Range: {{ $request->start_time }} to {{ $request->end_time ?? 'End of day' }}</li>
            @endif
            @if($request->product_id)
                <li>Product: {{ $stockOuts->first()?->product->name ?? 'N/A' }}</li>
            @endif
            @if($request->customer_name)
                <li>Customer: {{ $request->customer_name }}</li>
            @endif
            @if($request->min_amount || $request->max_amount)
                <li>Amount Range: 
                    @if($request->min_amount) ${{ number_format($request->min_amount, 2) }} @endif
                    @if($request->min_amount && $request->max_amount) to @endif
                    @if($request->max_amount) ${{ number_format($request->max_amount, 2) }} @endif
                </li>
            @endif
        </ul>
    </div>

    <div class="summary row">
        <div class="col-6">
            <h5>Total Quantity: {{ number_format($totalQuantity) }}</h5>
        </div>
        <div class="col-6">
            <h5>Total Revenue: ${{ number_format($totalRevenue, 2) }}</h5>
        </div>
    </div>

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
            @foreach($stockOuts as $stockOut)
                <tr>
                    <td>{{ $stockOut->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $stockOut->product->name }}</td>
                    <td>{{ $stockOut->customer_name }}</td>
                    <td>{{ number_format($stockOut->quantity) }}</td>
                    <td>${{ number_format($stockOut->unit_price, 2) }}</td>
                    <td>${{ number_format($stockOut->total_price, 2) }}</td>
                    <td>{{ $stockOut->notes }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Totals:</strong></td>
                <td><strong>{{ number_format($totalQuantity) }}</strong></td>
                <td></td>
                <td><strong>${{ number_format($totalRevenue, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>End of Report</p>
    </div>

    <div class="no-print mt-4">
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <script>
        window.onload = function() {
            if (!window.location.search.includes('no_print')) {
                window.print();
            }
        }
    </script>
</body>
</html> 