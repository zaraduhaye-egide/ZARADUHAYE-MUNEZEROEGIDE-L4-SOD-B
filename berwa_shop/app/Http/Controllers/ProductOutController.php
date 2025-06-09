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
        // Get stock outs with their related products
        $stockOuts = ProductOut::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get products with available stock and unit price
        $products = Product::all()->map(function ($product) {
            $availableStock = $product->available_stock;
            $unitPrice = $product->unit_price ?? 0;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'unit_price' => number_format($unitPrice, 2, '.', ''),
                'formatted_unit_price' => number_format($unitPrice, 2),
                'available_stock' => $availableStock,
                'total_value' => $availableStock * $unitPrice
            ];
        })->filter(function ($product) {
            return $product['available_stock'] > 0;
        })->values();
        
        return view('stock.out', compact('stockOuts', 'products'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
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
            
            // Get available stock
            $availableStock = $product->available_stock;

            // Check if there's enough stock
            if ($request->quantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => "Insufficient stock. Only {$availableStock} units available."]);
            }

            // Clean and format the unit price
            $unitPrice = floatval(str_replace(['$', ','], '', $request->unit_price));
            
            // Calculate total price
            $totalPrice = $request->quantity * $unitPrice;

            // Create the stock out record
            $stockOut = ProductOut::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out recorded successfully for ' . $product->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while recording the stock out. Please try again.']);
        }
    }

    public function edit(ProductOut $stockOut)
    {
        try {
            $stockOut->load('product');
            
            // Get products with their available stock
            $products = Product::all()->map(function ($product) use ($stockOut) {
                $totalIn = ProductIn::where('product_id', $product->id)->sum('quantity');
                $totalOut = ProductOut::where('product_id', $product->id)
                    ->where('id', '!=', $stockOut->id) // Exclude current stock out
                    ->sum('quantity');
                $product->available_stock = $totalIn - $totalOut;
                return $product;
            });
            
            return view('stock.out-edit', compact('stockOut', 'products'));
        } catch (\Exception $e) {
            return redirect()->route('product-outs.index')
                ->with('error', 'Error loading stock out record: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ProductOut $stockOut)
    {
        // Validate the request
        $validated = $request->validate([
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
            
            // Calculate available stock (excluding current record)
            $availableStock = $product->available_stock + $stockOut->quantity; // Add back current stock out quantity

            // Check if there's enough stock
            if ($request->quantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => "Insufficient stock. Only {$availableStock} units available."]);
            }

            // Clean and format the unit price
            $unitPrice = floatval(str_replace(['$', ','], '', $request->unit_price));
            
            // Calculate total price
            $totalPrice = $request->quantity * $unitPrice;

            // Update the stock out record
            $stockOut->update([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the stock out. Please try again.']);
        }
    }

    public function destroy(ProductOut $stockOut)
    {
        try {
            $stockOut->delete();
            return redirect()->route('product-outs.index')
                ->with('success', 'Stock out record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while deleting the stock out record.']);
        }
    }
}
