<!DOCTYPE html>
<html>
<head>
    <title>Stock In Report - {{ date('Y-m-d') }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>BERWA SHOP</h2>
        <h3>Stock In Report</h3>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="summary row">
        <div class="col-6">
            <h5>Total Quantity: {{ number_format($totalQuantity) }}</h5>
        </div>
        <div class="col-6">
            <h5>Total Cost: ${{ number_format($totalCost, 2) }}</h5>
        </div>
    </div>

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
            @foreach($stockIns as $stockIn)
                <tr>
                    <td>{{ $stockIn->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $stockIn->product->name }}</td>
                    <td>{{ number_format($stockIn->quantity) }}</td>
                    <td>${{ number_format($stockIn->unit_cost, 2) }}</td>
                    <td>${{ number_format($stockIn->quantity * $stockIn->unit_cost, 2) }}</td>
                    <td>{{ $stockIn->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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