<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\AvatarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product moved to trash!');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(10);

        return view('products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trash')->with('success', 'Product restored successfully!');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Delete photo if exists
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->forceDelete();

        return redirect()->route('products.trash')->with('success', 'Product permanently deleted!');
    }

    public function exportPdf()
    {
        $products = Product::with('category')->latest()->get();
        $total_value = $products->sum(function ($product) {
            return $product->price * $product->stock;
        });
        $low_stock_count = $products->where('stock', '<', 5)->count();

        $pdf = Pdf::loadView('pdfs.products', compact('products', 'total_value', 'low_stock_count'))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $filename = 'products_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}