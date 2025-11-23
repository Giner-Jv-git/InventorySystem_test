@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('categories.index') }}" style="font-size: 24px; color: var(--teal); text-decoration: none;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--text-primary);">Edit Category</h1>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Update category details</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="card mb-8">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label>Category Name <span style="color: var(--coral);">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" placeholder="e.g., Electronics" required>
                    @error('name')
                    <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="description" value="{{ old('description', $category->description) }}" placeholder="Brief description...">
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('categories.index') }}" class="btn-secondary">
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
                <p style="color: var(--text-secondary); font-size: 14px;">Once deleted, this category cannot be recovered.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');" class="mt-4">
            @csrf
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash mr-2"></i> Delete Category
            </button>
        </form>
    </div>
</div>
@endsection
