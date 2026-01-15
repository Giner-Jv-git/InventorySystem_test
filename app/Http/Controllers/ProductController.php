<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\AvatarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'product_' . time() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('photos/products', $filename, 'public');
            $validated['photo'] = $photoPath;
        }

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
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }

            $file = $request->file('photo');
            $filename = 'product_' . time() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('photos/products', $filename, 'public');
            $validated['photo'] = $photoPath;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete photo if exists
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}