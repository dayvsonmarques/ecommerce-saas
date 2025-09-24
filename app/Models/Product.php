<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'stock',
        'category_id',
        'is_active',
        'peso',
        'comprimento',
        'largura',
        'altura',
        'sku',
        'marca',
        'material',
        'tamanhos',
        'cores',
        'estoque_minimo',
        'controlar_estoque',
        'meta_titulo',
        'meta_descricao',
        'tags'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'peso' => 'decimal:3',
        'comprimento' => 'decimal:2',
        'largura' => 'decimal:2',
        'altura' => 'decimal:2',
        'tamanhos' => 'array',
        'cores' => 'array',
        'controlar_estoque' => 'boolean',
        'tags' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }
}
