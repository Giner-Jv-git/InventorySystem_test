<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'stock', 'category_id', 'photo'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relationship: A product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
