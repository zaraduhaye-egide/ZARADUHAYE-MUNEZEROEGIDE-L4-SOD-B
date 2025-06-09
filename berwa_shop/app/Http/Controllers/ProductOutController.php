<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOut;
use App\Models\ProductIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOutController extends Controller
{
    public function index()
    {
        $stockOuts = ProductOut::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $products = Product::all();
        return view('stock.out', compact('stockOuts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0.01',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get the product
            $product = Product::findOrFail($request->product_id);

            // Calculate total stock in
            $totalStockIn = ProductIn::where('product_id', $product->id)->sum('quantity');
            
            // Calculate total stock out
            $totalStockOut = ProductOut::where('product_id', $product->id)->sum('quantity');
            
            // Calculate available stock
            $availableStock = $totalStockIn - $totalStockOut;

            // Check if there's enough stock
            if ($request->quantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => "Insufficient stock. Only {$availableStock} units available."]);
            }

            // Calculate total price
            $totalPrice = $request->quantity * $request->unit_price;

            // Create the stock out record
            ProductOut::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $totalPrice,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product-outs.index')
                ->with('error', 'Error recording stock out: ' . $e->getMessage());
        }
    }

    public function edit(ProductOut $stockOut)
    {
        try {
            $stockOut->load('product');
            $products = Product::all();
            
            // Calculate available stock for each product
            foreach ($products as $product) {
                $totalIn = ProductIn::where('product_id', $product->id)->sum('quantity');
                $totalOut = ProductOut::where('product_id', $product->id)
                    ->where('id', '!=', $stockOut->id)
                    ->sum('quantity');
                $product->available_stock = $totalIn - $totalOut;
            }
            
            return view('stock.out-edit', compact('stockOut', 'products'));
        } catch (\Exception $e) {
            return redirect()->route('product-outs.index')
                ->with('error', 'Error loading stock out record: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ProductOut $stockOut)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0.01',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total stock in
            $totalStockIn = ProductIn::where('product_id', $request->product_id)->sum('quantity');
            
            // Calculate total stock out (excluding current record)
            $totalStockOut = ProductOut::where('product_id', $request->product_id)
                ->where('id', '!=', $stockOut->id)
                ->sum('quantity');
            
            // Calculate available stock
            $availableStock = $totalStockIn - $totalStockOut;

            // Check if there's enough stock
            if ($request->quantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => "Insufficient stock. Only {$availableStock} units available."]);
            }

            // Calculate total price
            $totalPrice = $request->quantity * $request->unit_price;

            // Update the stock out record
            $stockOut->update([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $totalPrice,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product-outs.index')
                ->with('error', 'Error updating stock out: ' . $e->getMessage());
        }
    }

    public function destroy(ProductOut $stockOut)
    {
        try {
            DB::beginTransaction();
            
            // Delete the stock out record
            $stockOut->delete();
            
            DB::commit();
            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product-outs.index')
                ->with('error', 'Error deleting stock out: ' . $e->getMessage());
        }
    }
}
