<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductInController extends Controller
{
    public function index()
    {
        $stockIns = ProductIn::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('stock.in', compact('stockIns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0.01',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Find or create the product
            $product = Product::firstOrCreate(
                ['name' => $request->product_name],
                [
                    'category' => $request->category,
                    'price' => $request->price,
                    'description' => $request->notes
                ]
            );

            // Create the stock in record
            ProductIn::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_cost' => $request->unit_cost,
                'supplier' => $request->supplier,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('stock.in.index')
                ->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stock.in.index')
                ->with('error', 'Error adding stock: ' . $e->getMessage());
        }
    }

    public function edit(ProductIn $stockIn)
    {
        $stockIn->load('product');
        return view('stock.edit', compact('stockIn'));
    }

    public function update(Request $request, ProductIn $stockIn)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0.01',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update product name if changed
            $product = $stockIn->product;
            if ($product->name !== $request->product_name) {
                $product->update(['name' => $request->product_name]);
            }

            // Update stock in record
            $stockIn->update([
                'quantity' => $request->quantity,
                'unit_cost' => $request->unit_cost,
                'supplier' => $request->supplier,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('stock.in.index')
                ->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stock.in.index')
                ->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }

    public function destroy(ProductIn $stockIn)
    {
        try {
            $stockIn->delete();
            return redirect()->route('stock.in.index')
                ->with('success', 'Stock deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('stock.in.index')
                ->with('error', 'Error deleting stock: ' . $e->getMessage());
        }
    }
}
