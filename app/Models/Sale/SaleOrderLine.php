<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderLine extends Model
{
    use HasFactory;
    protected $table = 'jual_detail';
    protected $primaryKey = 'id';
    
    
    protected $fillable = [
        'id', 'order_id', 'variant_id', 'product_id', 'qty', 'price', 'unit_id', 'subtotal'
    ];
    
    public function sale(){
        return $this->belongsTo(SaleOrder::class, 'sale_id');
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
