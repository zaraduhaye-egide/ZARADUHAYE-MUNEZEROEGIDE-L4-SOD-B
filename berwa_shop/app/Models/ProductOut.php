<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'customer_name',
        'notes'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
} 