@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="card mb-6" style="border-left: 4px solid #7CB9C8; background: #E8F5F7;">
        <div style="color: #1A7A8A; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid var(--coral);">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                         Total Products
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: var(--coral);">{{ $totalProducts }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Active inventory items</p>
        </div>

        <!-- Total Categories -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid var(--teal);">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Categories
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: var(--teal);">{{ $totalCategories }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Organized by type</p>
        </div>

        <!-- Low Stock Items -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid #E8997A;">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Low Stock
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: #E8997A;">{{ $lowStock }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Need attention soon</p>
        </div>

        <!-- Reorder Rate -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid #7CB9C8;">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Reorder Rate
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: #7CB9C8;">{{ $reorderRate }}<span style="font-size: 24px;">%</span></p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Of total inventory</p>
        </div>
    </div>

    <!-- Category Overview & Products -->
    <div class="card mb-8">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Category Overview & Products</h2>
        </div>

        <!-- Search Bar -->
        <div style="margin-bottom: 24px;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                <input 
                    type="text" 
                    id="categorySearch" 
                    placeholder="Search categories or products..." 
                    style="width: 100%; padding-left: 44px; background: var(--light-gray);"
                />
            </div>
        </div>

        <!-- Category Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Category Name</th>
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Products in Category</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Total Products</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Status</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTable">
                    @forelse($categories as $category)
                    <tr class="category-row" data-category="{{ strtolower($category->name) }}" data-products="{{ strtolower($category->products->pluck('name')->join(' ')) }}" style="border-bottom: 1px solid var(--light-gray); transition: all 0.2s ease; cursor: pointer;" onclick="toggleProductList(this, event)">
                        <td style="padding: 16px; color: var(--text-primary); font-weight: 600;">
                            <i class="fas fa-chevron-right" style="margin-right: 8px; transition: transform 0.2s ease; display: inline-block;" class="toggle-icon"></i>
                            {{ $category->name }}
                        </td>
                        <td style="padding: 16px; color: var(--text-primary);">
                            <span class="product-count">{{ $category->products_count }} item{{ $category->products_count != 1 ? 's' : '' }}</span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: var(--text-primary); font-weight: 600;">{{ $category->products_count }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @php
                                $lowStockInCategory = $category->products->where($qtyCol, '<', 5)->count();
                            @endphp
                            @if($lowStockInCategory > 0)
                                <span style="background: #FFF3CD; color: #856404; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block;">Low Stock</span>
                            @else
                                <span style="background: #D4EDDA; color: #155724; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block;">Active</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('categories.edit', $category->id) }}" onclick="event.stopPropagation()" style="color: var(--teal); text-decoration: none; font-weight: 600; transition: color 0.2s ease;" onmouseover="this.style.color='var(--coral)'" onmouseout="this.style.color='var(--teal)'">View Details</a>
                        </td>
                    </tr>
                    <!-- Products Dropdown Row -->
                    <tr class="products-dropdown" style="display: none; background: #FAFAF8;">
                        <td colspan="5" style="padding: 0;">
                            <div style="padding: 16px 16px 16px 64px; border-top: 1px solid var(--light-gray);">
                                <p style="color: var(--text-secondary); font-size: 12px; text-transform: uppercase; font-weight: 600; margin-bottom: 12px;">Products in Category</p>
                                @if($category->products_count > 0)
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                                        @foreach($category->products as $product)
                                        <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid var(--light-gray); transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                                            <p style="color: var(--text-primary); font-weight: 600; font-size: 13px; margin-bottom: 6px;">{{ $product->name }}</p>
                                            <p style="color: var(--text-secondary); font-size: 12px;">
                                                <i class="fas fa-cube" style="margin-right: 4px;"></i>
                                                Stock: <strong>{{ $product->$qtyCol }}</strong>
                                            </p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p style="color: var(--text-secondary); font-style: italic;">No products</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: var(--text-secondary);">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- No Results Message -->
        <div id="noResults" style="display: none; text-align: center; padding: 32px; color: var(--text-secondary);">
            <i class="fas fa-search" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
            <p>No categories or products found matching your search</p>
        </div>

        <script>
            function toggleProductList(row, event) {
                // Don't toggle if clicking on the View Details link
                if (event.target.closest('a')) {
                    return;
                }

                const nextRow = row.nextElementSibling;
                const icon = row.querySelector('.toggle-icon');
                
                if (nextRow && nextRow.classList.contains('products-dropdown')) {
                    if (nextRow.style.display === 'none' || nextRow.style.display === '') {
                        nextRow.style.display = 'table-row';
                        icon.style.transform = 'rotate(90deg)';
                    } else {
                        nextRow.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }

            const searchInput = document.getElementById('categorySearch');
            const categoryRows = document.querySelectorAll('.category-row');
            const noResults = document.getElementById('noResults');
            const categoryTable = document.getElementById('categoryTable');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleRows = 0;

                categoryRows.forEach(row => {
                    const categoryName = row.getAttribute('data-category');
                    const productNames = row.getAttribute('data-products');
                    const nextRow = row.nextElementSibling;
                    
                    if (categoryName.includes(searchTerm) || productNames.includes(searchTerm)) {
                        row.style.display = '';
                        // Hide dropdown when searching
                        if (nextRow && nextRow.classList.contains('products-dropdown')) {
                            nextRow.style.display = 'none';
                            row.querySelector('.toggle-icon').style.transform = 'rotate(0deg)';
                        }
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                        // Also hide the corresponding dropdown
                        if (nextRow && nextRow.classList.contains('products-dropdown')) {
                            nextRow.style.display = 'none';
                        }
                    }
                });

                // Show/hide no results message
                if (visibleRows === 0) {
                    categoryTable.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    categoryTable.style.display = '';
                    noResults.style.display = 'none';
                }
            });
        </script>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('products.index') }}" class="btn-primary w-full text-center block">
                    <i class="fas fa-plus mr-2"></i> Add New Product
                </a>
                <a href="{{ route('categories.index') }}" class="btn-secondary w-full text-center block">
                    <i class="fas fa-plus mr-2"></i> Add New Category
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-bold mb-4">Inventory Health</h3>
            <div style="background: var(--light-gray); border-radius: 12px; padding: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="color: var(--text-secondary); font-weight: 600;">In Stock</span>
                    <span style="color: var(--coral); font-weight: 700;">{{ $totalProducts - $lowStock }}</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <?php $percentage = $totalProducts ? round(((($totalProducts - $lowStock) / $totalProducts) * 100)) : 0; ?>
                    <div class="progress-bar" data-width="{{ $percentage }}" style="height: 100%; background: linear-gradient(90deg, var(--coral), var(--teal)); transition: width 0.3s ease;"></div>
                </div>
                <script>
                    document.querySelectorAll('.progress-bar').forEach(el => {
                        el.style.width = el.dataset.width + '%';
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
