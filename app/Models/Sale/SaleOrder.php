<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $table = 'jual';
    protected $primaryKey = 'id';

    
    public function customer(){
        return $this->belongsTo(\App\Models\User::class, 'customer_id');
    }

    public function shipping(){
        return $this->belongsTo(\App\Models\UserAddress::class, 'delivery_id');
    }

    public function lines()
    {
        return $this->hasMany(SaleOrderLine::class, 'order_id');
    }
}
