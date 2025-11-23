@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('products.index') }}" style="font-size: 24px; color: var(--teal); text-decoration: none;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--text-primary);">Edit Product</h1>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Update product details</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="card mb-8">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label>Product Name <span style="color: var(--coral);">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g., Wireless Mouse" required>
                    @error('name')
                    <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label>Price <span style="color: var(--coral);">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" placeholder="0.00" required>
                        @error('price')
                        <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label>Stock <span style="color: var(--coral);">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="0" required>
                        @error('stock')
                        <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Delete Section -->
    <div class="card" style="border-left: 4px solid #E8997A; background: rgba(232, 153, 122, 0.05);">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-bold mb-2" style="color: #D67A5F;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone
                </h2>
                <p style="color: var(--text-secondary); font-size: 14px;">Once deleted, this product cannot be recovered.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');" class="mt-4">
            @csrf
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash mr-2"></i> Delete Product
            </button>
        </form>
    </div>
</div>
@endsection
