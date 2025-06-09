<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function stockIn(Request $request)
    {
        $query = ProductIn::with('product');

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $stockIns = $query->orderBy('created_at', 'desc')->get();
        $products = Product::all();

        // Calculate totals
        $totalQuantity = $stockIns->sum('quantity');
        $totalCost = $stockIns->sum(function($item) {
            return $item->quantity * $item->unit_cost;
        });

        if ($request->print) {
            return view('reports.stock-in-print', compact('stockIns', 'totalQuantity', 'totalCost'));
        }

        return view('reports.stock-in', compact('stockIns', 'products', 'totalQuantity', 'totalCost'));
    }

    public function stockOut(Request $request)
    {
        $query = ProductOut::with('product');

        // Handle time period filters
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                         ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                         ->whereYear('created_at', Carbon::now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'last_year':
                    $query->whereYear('created_at', Carbon::now()->subYear()->year);
                    break;
            }
        }

        // Handle custom date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Handle time range (for hourly filtering)
        if ($request->filled('start_time')) {
            $query->whereTime('created_at', '>=', $request->start_time);
        }
        if ($request->filled('end_time')) {
            $query->whereTime('created_at', '<=', $request->end_time);
        }

        // Handle other filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }
        if ($request->filled('min_amount')) {
            $query->where('total_price', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('total_price', '<=', $request->max_amount);
        }

        // Get results
        $stockOuts = $query->orderBy('created_at', 'desc')->get();
        $products = Product::all();

        // Calculate totals
        $totalQuantity = $stockOuts->sum('quantity');
        $totalRevenue = $stockOuts->sum('total_price');

        // Group by date for chart data
        $dailyData = $stockOuts->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($group) {
            return [
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum('total_price')
            ];
        });

        if ($request->print) {
            return view('reports.stock-out-print', compact(
                'stockOuts', 
                'totalQuantity', 
                'totalRevenue',
                'request'
            ));
        }

        return view('reports.stock-out', compact(
            'stockOuts', 
            'products', 
            'totalQuantity', 
            'totalRevenue',
            'dailyData'
        ));
    }

    public function currentStock(Request $request)
    {
        $query = Product::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('current_stock', '>', 10);
                    break;
                case 'low_stock':
                    $query->whereBetween('current_stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('current_stock', '<=', 0);
                    break;
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Get products with calculated current stock
        $products = $query->select('products.*')
            ->selectRaw('(SELECT COALESCE(SUM(quantity), 0) FROM product_ins WHERE product_id = products.id) - 
                        (SELECT COALESCE(SUM(quantity), 0) FROM product_outs WHERE product_id = products.id) as current_stock')
            ->orderBy('name')
            ->paginate(15);

        // Get unique categories
        $categories = Product::distinct('category')->pluck('category');

        // Calculate summary statistics
        $totalProducts = $products->total();
        $totalStockValue = $products->sum(function($product) {
            return $product->current_stock * $product->price;
        });
        $lowStockCount = $products->filter(function($product) {
            return $product->current_stock > 0 && $product->current_stock <= 10;
        })->count();
        $outOfStockCount = $products->filter(function($product) {
            return $product->current_stock <= 0;
        })->count();

        return view('reports.current-stock', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStockValue',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function analytics(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $previousStartDate = Carbon::parse($startDate)->subDays(Carbon::parse($startDate)->diffInDays($endDate));

        // Calculate current period metrics
        $currentPeriodQuery = ProductOut::whereBetween('created_at', [$startDate, $endDate]);
        $totalRevenue = $currentPeriodQuery->sum('total_price');
        $totalTransactions = $currentPeriodQuery->count();
        $averageOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Calculate previous period metrics for comparison
        $previousPeriodQuery = ProductOut::whereBetween('created_at', [$previousStartDate, $startDate]);
        $previousRevenue = $previousPeriodQuery->sum('total_price');
        $previousTransactions = $previousPeriodQuery->count();
        $previousAOV = $previousTransactions > 0 ? $previousRevenue / $previousTransactions : 0;

        // Calculate growth percentages
        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 100;
        $transactionGrowth = $previousTransactions > 0 ? (($totalTransactions - $previousTransactions) / $previousTransactions) * 100 : 100;
        $aovGrowth = $previousAOV > 0 ? (($averageOrderValue - $previousAOV) / $previousAOV) * 100 : 100;

        // Calculate stock turnover rate
        $averageInventory = Product::join('product_ins', 'products.id', '=', 'product_ins.product_id')
            ->whereBetween('product_ins.created_at', [$startDate, $endDate])
            ->avg('product_ins.quantity');
        $totalSold = ProductOut::whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        $stockTurnoverRate = $averageInventory > 0 ? $totalSold / $averageInventory : 0;

        // Previous period turnover rate for comparison
        $previousAverageInventory = Product::join('product_ins', 'products.id', '=', 'product_ins.product_id')
            ->whereBetween('product_ins.created_at', [$previousStartDate, $startDate])
            ->avg('product_ins.quantity');
        $previousTotalSold = ProductOut::whereBetween('created_at', [$previousStartDate, $startDate])->sum('quantity');
        $previousTurnoverRate = $previousAverageInventory > 0 ? $previousTotalSold / $previousAverageInventory : 0;
        $turnoverGrowth = $previousTurnoverRate > 0 ? (($stockTurnoverRate - $previousTurnoverRate) / $previousTurnoverRate) * 100 : 100;

        // Prepare chart data
        $revenueChart = (object)[
            'labels' => [],
            'data' => []
        ];
        $dailyRevenue = ProductOut::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        foreach ($dailyRevenue as $day) {
            $revenueChart->labels[] = $day->date;
            $revenueChart->data[] = $day->revenue;
        }

        // Top products data
        $topProducts = Product::select(
            'products.id',
            'products.name',
            'products.category',
            'products.price',
            'products.description'
        )
            ->selectRaw('SUM(product_outs.quantity) as units_sold')
            ->selectRaw('SUM(product_outs.total_price) as revenue')
            ->selectRaw('(SUM(product_outs.total_price) - SUM(product_outs.quantity * products.price)) / SUM(product_outs.total_price) * 100 as profit_margin')
            ->selectRaw('(SELECT COALESCE(SUM(quantity), 0) FROM product_ins WHERE product_id = products.id) - 
                        (SELECT COALESCE(SUM(quantity), 0) FROM product_outs WHERE product_id = products.id) as current_stock')
            ->join('product_outs', 'products.id', '=', 'product_outs.product_id')
            ->whereBetween('product_outs.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name', 'products.category', 'products.price', 'products.description')
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get();

        // Products chart data
        $productsChart = (object)[
            'labels' => $topProducts->pluck('name')->toArray(),
            'data' => $topProducts->pluck('units_sold')->toArray()
        ];

        return view('reports.analytics', compact(
            'totalRevenue',
            'totalTransactions',
            'averageOrderValue',
            'stockTurnoverRate',
            'revenueGrowth',
            'transactionGrowth',
            'aovGrowth',
            'turnoverGrowth',
            'revenueChart',
            'productsChart',
            'topProducts'
        ));
    }
} 