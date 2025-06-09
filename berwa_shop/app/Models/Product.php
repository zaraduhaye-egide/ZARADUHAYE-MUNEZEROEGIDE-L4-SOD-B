<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
    ];

    public function stockIns(): HasMany
    {
        return $this->hasMany(ProductIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(ProductOut::class);
    }

    public function getCurrentStockAttribute(): int
    {
        return $this->stockIns()->sum('quantity') - $this->stockOuts()->sum('quantity');
    }
} 