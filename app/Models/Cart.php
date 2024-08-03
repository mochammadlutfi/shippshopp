<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = [

        'product_id', 'variant_id', 'user_id', 'qty', 'price'
    ];

    public function product()
    {
        return $this->belongsTo(Inventory\Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(Inventory\ProductVariant::class, 'variant_id');
    }


}
