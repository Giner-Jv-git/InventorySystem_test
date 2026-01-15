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
        <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data">
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
                <div>
                    <label>Photo <span style="color: var(--text-secondary); font-size: 12px;">(JPG/PNG, max 2MB)</span></label>
                    <div style="display: flex; gap: 16px; align-items: flex-start;">
                        <div style="flex: 1;">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png" style="padding: 10px; border: 2px solid var(--light-gray); border-radius: 6px; width: 100%; cursor: pointer;">
                            @error('photo')
                            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; background: var(--light-gray); display: flex; align-items: center; justify-content: center;">
                            @if($category->photo)
                                <img id="photoPreview" src="{{ asset('storage/' . $category->photo) }}" alt="Photo preview" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div id="photoPreview" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #E89B7A, #7CB9C8); color: white; font-weight: bold; font-size: 20px;">
                                    {{ \App\Helpers\AvatarHelper::getInitials($category->name) }}
                                </div>
                            @endif
                        </div>
                    </div>
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
                    <i class="fas fa-exclamation-triangle mr-2"></i> Delete Zone
                </h2>
                <p style="color: var(--text-secondary); font-size: 14px;">Delete this category. You can restore it from trash later.</p>
            </div>
        </div>
        <div class="mt-4">
            <button type="button" class="btn-danger" onclick="openDeleteModal('{{ $category->id }}', 'category', '{{ $category->name }}')">
                <i class="fas fa-trash mr-2"></i> Move to Trash
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="card" style="max-width: 400px; width: 90%;">
        <h3 style="color: var(--coral); font-size: 20px; font-weight: bold; margin-bottom: 12px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Delete
        </h3>
        <p style="color: var(--text-secondary); margin-bottom: 20px;" id="deleteMessage"></p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button onclick="closeDeleteModal()" class="btn-secondary" style="background: var(--light-gray); color: var(--text-primary); padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                Cancel
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" style="background: var(--coral); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = `<img src="${event.target.result}" alt="Photo preview" style="width: 100%; height: 100%; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);
    }
});

function openDeleteModal(id, type, name) {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const form = document.getElementById('deleteForm');
    
    message.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    
    if (type === 'category') {
        form.action = '/categories/' + id;
    }
    
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
