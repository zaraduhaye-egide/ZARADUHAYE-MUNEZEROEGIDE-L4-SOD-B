@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Reports Dashboard</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Out Reports</h5>
                                    <p class="card-text">View detailed reports of all stock out transactions with advanced filtering options.</p>
                                    <a href="{{ route('reports.stock-out') }}" class="btn btn-primary">
                                        <i class="fas fa-file-export"></i> View Stock Out Reports
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Stock In Reports</h5>
                                    <p class="card-text">Access comprehensive reports of all stock in transactions and inventory additions.</p>
                                    <a href="{{ route('reports.stock-in') }}" class="btn btn-primary">
                                        <i class="fas fa-file-import"></i> View Stock In Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Current Stock Report</h5>
                                    <p class="card-text">Get an overview of current inventory levels and product status.</p>
                                    <a href="{{ route('reports.current-stock') }}" class="btn btn-primary">
                                        <i class="fas fa-boxes"></i> View Current Stock
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Analytics Dashboard</h5>
                                    <p class="card-text">View analytics and insights about your inventory movements and trends.</p>
                                    <a href="{{ route('reports.analytics') }}" class="btn btn-primary">
                                        <i class="fas fa-chart-line"></i> View Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 