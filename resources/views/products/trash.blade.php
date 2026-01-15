@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('products.index') }}" style="font-size: 24px; color: var(--teal); text-decoration: none;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--text-primary);">
                <i class="fas fa-trash mr-2" style="color: var(--coral);"></i>Product Trash
            </h1>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Deleted products - Restore or permanently delete</p>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="card mb-6" style="border-left: 4px solid var(--teal); background: #E8F5F7;">
        <div style="color: #1A7A8A; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Trashed Products List -->
    <div class="card">
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Photo</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Category</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Stock</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Deleted</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr style="border-bottom: 1px solid var(--light-gray); transition: background 0.2s ease; opacity: 0.7;" onmouseover="this.style.background='rgba(232, 153, 122, 0.05)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.7'">
                        <td class="px-6 py-4">
                            <div style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden; background: var(--light-gray); display: flex; align-items: center; justify-content: center;">
                                @if($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #E89B7A, #7CB9C8); color: white; font-weight: bold; font-size: 14px;">
                                        {{ \App\Helpers\AvatarHelper::getInitials($product->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--text-primary);">📦 {{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--coral);">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--text-primary);">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                            {{ $product->deleted_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <form method="POST" action="{{ route('products.restore', $product->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-sm font-bold" style="color: #1A7A8A; background: none; border: none; cursor: pointer; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='#1A7A8A'">
                                        <i class="fas fa-undo mr-1"></i> Restore
                                    </button>
                                </form>
                                <button type="button" class="text-sm font-bold" style="color: var(--coral); background: none; border: none; cursor: pointer; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D67A5F'" onmouseout="this.style.color='var(--coral)'" onclick="openDeleteModal('{{ $product->id }}', 'product', '{{ $product->name }}')">
                                    <i class="fas fa-times mr-1"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; margin-bottom: 16px;">🗑️</div>
            <p style="color: var(--text-secondary); font-size: 16px; font-weight: 500;">Trash is empty</p>
            <p style="color: var(--text-secondary); font-size: 13px; margin-top: 8px;">Deleted products will appear here</p>
        </div>
        @endif

        <!-- Pagination -->
        @if($products->count() > 0)
        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="card mt-8" style="background: rgba(124, 185, 200, 0.05); border-left: 4px solid #7CB9C8;">
        <div style="color: #1A7A8A;">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Soft Delete:</strong> Items in trash can be restored. Use "Delete" to permanently remove them.
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="card" style="max-width: 400px; width: 90%;">
        <h3 style="color: var(--coral); font-size: 20px; font-weight: bold; margin-bottom: 12px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> Permanently Delete
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
function openDeleteModal(id, type, name) {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const form = document.getElementById('deleteForm');
    
    message.textContent = `Permanently delete "${name}"? This action cannot be undone.`;
    
    if (type === 'product') {
        form.action = '/products/' + id + '/force';
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
