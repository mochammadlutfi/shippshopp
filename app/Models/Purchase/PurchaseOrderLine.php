<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    use HasFactory;
    protected $table = 'beli_detail';
    protected $primaryKey = 'id';
    
    
    protected $fillable = [
        'id', 'order_id', 'variant_id', 'product_id', 'qty', 'price', 'unit_id', 'subtotal'
    ];
    public function purchase(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_id');
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
