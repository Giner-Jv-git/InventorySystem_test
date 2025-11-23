<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        $categories = Category::all();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // detect available columns
        $qtyCol = Schema::hasColumn('products', 'quantity')
            ? 'quantity'
            : 'stock';

        $lowStock = Product::where($qtyCol, '<', 5)->count();
        $inventoryValue = Product::sum(DB::raw('COALESCE(price,0) * COALESCE(' . $qtyCol . ',0)')) ?? 0;
        $reorderRate = $totalProducts ? round(($lowStock / $totalProducts) * 100, 0) : 0;

        return view('products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'totalCategories',
            'lowStock',
            'inventoryValue',
            'reorderRate'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}