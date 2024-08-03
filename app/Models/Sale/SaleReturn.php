<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;
    protected $table = 'sale_return';
    protected $primaryKey = 'id';

    
    public function customer(){
        return $this->belongsTo(\App\Models\User::class, 'customer_id');
    }

    public function order(){
        return $this->belongsTo(SaleOrder::class, 'order_id');
    }

    public function lines()
    {
        return $this->hasMany(SaleReturnLine::class, 'return_id');
    }
}
