<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dipantry\Rajaongkir\Models\ROProvince;
use Dipantry\Rajaongkir\Models\ROCity;
class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'reciever', 'phone', 'address', 'postal_code', 'area_id', 'lat', 'lng', 'is_primary', 'user_id'
    ];

    public function province()
    {
        return $this->belongsTo(ROProvince::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(ROCity::class, 'city_id');
    }
}
