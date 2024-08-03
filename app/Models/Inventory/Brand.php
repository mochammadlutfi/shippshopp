<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'merk';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'image',
    ];

    public function product()
    {
        return $this->hasOne(Product::Class, 'merk_id');
    }

}
