<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnLine extends Model
{
    use HasFactory;
    protected $table = 'sale_return_lines';
    protected $primaryKey = 'id';
    
    
    protected $fillable = [
        'id', 'order_id', 'variant_id', 'product_id', 'qty', 'price', 'unit_id', 'subtotal'
    ];
    
    public function sale(){
        return $this->belongsTo(SaleReturn::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
    }

    
    public function unit()
    {
        return $this->belongsTo(\App\Models\Inventory\Unit::class, 'unit_id');
    }
}
