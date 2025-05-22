<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'category_id',
        'price',
        'stock',
        'image',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }
}
