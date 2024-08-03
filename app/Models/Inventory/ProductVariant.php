<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class ProductVariant extends Model
{

    protected $table = 'produk_varian';
    protected $primaryKey = 'id';

    protected $casts = [
        'purchase_price' => 'float',
        'sell_price' => 'float',
    ];

    protected $fillable = [
        'variant', 'sku', 'purchase_price', 'sell_price', 'product_id',
    ];

    
    protected $appends = [
        // 'current_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function sale()
    {
        return $this->hasOne(SaleLine::class, 'variant_id');
    }

    public function purchase_unit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }
}
