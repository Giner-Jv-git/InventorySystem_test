<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\AvatarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'category_' . time() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('photos/categories', $filename, 'public');
            $validated['photo'] = $photoPath;
        }

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }

            $file = $request->file('photo');
            $filename = 'category_' . time() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('photos/categories', $filename, 'public');
            $validated['photo'] = $photoPath;
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category moved to trash!');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->withCount('products')->latest('deleted_at')->get();

        return view('categories.trash', compact('categories'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('categories.trash')->with('success', 'Category restored successfully!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        // Delete photo if exists
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->forceDelete();

        return redirect()->route('categories.trash')->with('success', 'Category permanently deleted!');
    }

    public function exportPdf()
    {
        $categories = Category::withCount('products')->latest()->get();
        $total_products = $categories->sum('products_count');

        $pdf = Pdf::loadView('pdfs.categories', compact('categories', 'total_products'))
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $filename = 'categories_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}