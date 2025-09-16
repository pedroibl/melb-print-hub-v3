<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'subcategory',
        'base_price',
        'pricing_options',
        'specifications',
        'design_templates',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'pricing_options' => 'array',
        'specifications' => 'array',
        'design_templates' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'base_price' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
