<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductIn extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_cost',
        'supplier',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
} 