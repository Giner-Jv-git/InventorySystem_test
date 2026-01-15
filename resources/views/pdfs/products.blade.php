<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #fff;
        }
        
        .header {
            background: linear-gradient(135deg, #E8997A 0%, #7CB9C8 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
            padding: 0 10px;
        }
        
        .meta div {
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #f5f5f5;
            border-top: 2px solid #E8997A;
            border-bottom: 2px solid #E8997A;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        table td {
            padding: 10px 12px;
            font-size: 11px;
            border-bottom: 1px solid #eee;
        }
        
        table tbody tr:hover {
            background-color: rgba(232, 153, 122, 0.05);
        }
        
        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #7CB9C8;
            margin: 20px 0;
            font-size: 12px;
        }
        
        .summary strong {
            color: #7CB9C8;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(232, 153, 122, 0.1), rgba(124, 185, 200, 0.1));
            color: #3A3A36;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 10px;
        }
        
        .badge.low-stock {
            background: rgba(232, 153, 122, 0.15);
            color: #D67A5F;
        }
        
        .badge.in-stock {
            background: rgba(124, 185, 200, 0.15);
            color: #1A7A8A;
        }
        
        .price {
            font-weight: 600;
            color: #E8997A;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Products Report</h1>
        <p>Inventory Management System</p>
    </div>
    
    <div class="meta">
        <div><strong>Generated:</strong> {{ now()->format('M d, Y H:i:s') }}</div>
        <div><strong>Total Products:</strong> {{ $products->count() }}</div>
        <div><strong>Total Value:</strong> ${{ number_format($total_value, 2) }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: center;">Stock</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td><strong>{{ $product->name }}</strong></td>
                <td>{{ $product->category?->name ?? '—' }}</td>
                <td style="text-align: right;" class="price">${{ number_format($product->price, 2) }}</td>
                <td style="text-align: center;">{{ $product->stock }}</td>
                <td>
                    @if($product->stock < 5)
                        <span class="badge low-stock">⚠️ Low Stock</span>
                    @else
                        <span class="badge in-stock">✓ In Stock</span>
                    @endif
                </td>
                <td>{{ $product->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <strong>Inventory Summary:</strong><br>
        Total Products: <strong>{{ $products->count() }}</strong> | 
        Total Inventory Value: <strong>${{ number_format($total_value, 2) }}</strong> | 
        Low Stock Items: <strong>{{ $low_stock_count }}</strong> |
        Average Price: <strong>${{ number_format($products->avg('price'), 2) }}</strong>
    </div>
    
    <div class="footer">
        <p>This is an automated report generated by the Inventory Management System.</p>
        <p>© {{ date('Y') }} Inventory System. All rights reserved.</p>
    </div>
</body>
</html>
