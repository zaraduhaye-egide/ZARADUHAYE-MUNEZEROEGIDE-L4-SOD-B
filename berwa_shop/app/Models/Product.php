<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit_price',
        'category'
    ];

    /**
     * Get all stock ins for the product
     */
    public function productIns(): HasMany
    {
        return $this->hasMany(ProductIn::class);
    }

    /**
     * Get all stock outs for the product
     */
    public function productOuts(): HasMany
    {
        return $this->hasMany(ProductOut::class);
    }

    /**
     * Get current stock level
     */
    public function getCurrentStockAttribute(): int
    {
        $stockIn = $this->productIns()->sum('quantity') ?? 0;
        $stockOut = $this->productOuts()->sum('quantity') ?? 0;
        return $stockIn - $stockOut;
    }
} 