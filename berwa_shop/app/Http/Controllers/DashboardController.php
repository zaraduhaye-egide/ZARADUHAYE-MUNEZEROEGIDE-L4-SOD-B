<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user() instanceof \App\Models\Shopkeeper) {
                return redirect()->route('login');
            }
            $response = $next($request);
            return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        });
    }

    public function index()
    {
        // Get statistics
        $totalProducts = Product::count();
        $totalStockIn = ProductIn::sum('quantity');
        $totalStockOut = ProductOut::sum('quantity');
        $currentStock = $totalStockIn - $totalStockOut;

        // Get recent activities
        $recentStockIns = ProductIn::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentStockOuts = ProductOut::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStockIn',
            'totalStockOut',
            'currentStock',
            'recentStockIns',
            'recentStockOuts'
        ));
    }

    public function reports()
    {
        return view('reports');
    }

    public function export()
    {
        // TODO: Implement report export functionality
        return back()->with('message', 'Export functionality coming soon!');
    }
}
